<?php

namespace shop\services\manage\Shop;

use shop\forms\manage\Shop\Product\QuantityForm;
use Yii;
use shop\entities\Meta;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Tag;
//use shop\forms\manage\Shop\Product\CategoriesForm;
use shop\forms\manage\Shop\Product\ModificationForm;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\forms\manage\Shop\Product\PriceForm;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use shop\repositories\Shop\BrandsRepository;
use shop\repositories\Shop\CategoryRepository;
use shop\repositories\Shop\ProductRepository;
use shop\repositories\Shop\TagRepository;
use shop\services\TransactionManager;

class ProductManageService
{
    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $transaction;

    public function __construct(
        ProductRepository $products,
        BrandsRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction
    )
    {
        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
    }

    public function create(ProductCreateForm $form): Product
    {
        if ($form->brandId) {
            $brand = $this->brands->get($form->brandId);
        }

        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $brand ? $brand->id : null,
            $category->id,
            $form->code,
            $form->name_ru,
            $form->name_en,
            $form->description_ru,
            $form->description_en,
            $form->quantity->quantity,
            new Meta(
                $form->meta->title_ru,
                $form->meta->title_en,
                $form->meta->description_ru,
                $form->meta->description_en,
                $form->meta->keywords_ru,
                $form->meta->keywords_en
            )
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach ($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            if ($value->value) {
                $product->setValue($value->id, $value->value);
            }
        }

        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tags->get($tagId);
            $product->assignTag($tag->id);
        }


        $this->transaction->wrap(function () use ($product, $form) {
            if ($form->tags->newNames) {
                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $product->assignTag($tag->id);
                }
            }
            $this->products->save($product);
        });

        $this->products->save($product);
        return $product;
    }

    public function edit($id, ProductEditForm $form): void
    {
        $product = $this->products->get($id);
        if ($form->brandId) {
            $brand = $this->brands->get($form->brandId);
        }
        $category = $this->categories->get($form->categories->main);

        $product->edit(
            $brand ? $brand->id : null,
            $form->code,
            $form->name_ru,
            $form->name_en,
            $form->description_ru,
            $form->description_en,
            new Meta(
                $form->meta->title_ru,
                $form->meta->title_en,
                $form->meta->description_ru,
                $form->meta->description_en,
                $form->meta->keywords_ru,
                $form->meta->keywords_en
            )
        );

        $product->changeMainCategory($category->id);

        $this->transaction->wrap(function () use ($form, $product) {

            $product->revokeCategories();
            $product->revokeTags();
            $this->products->save($product);

            foreach ($form->categories->others as $otherId) {
                $category = $this->categories->get($otherId);
                $product->assignCategory($category->id);
            }

            foreach ($form->values as $value) {
                if ($value->value) {
                    $product->setValue($value->id, $value->value);
                }
            }

            foreach ($form->tags->existing as $tagId) {
                $tag = $this->tags->get($tagId);
                $product->assignTag($tag->id);
            }

            if ($form->tags->newNames) {
                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $product->assignTag($tag->id);
                }
            }
            $product->updateReviews($product->reviews);
            $this->products->save($product);
        });
    }

    public function changeQuantity($id, QuantityForm $form): void
    {
        $product = $this->products->get($id);
        $product->changeQuantity($form->quantity);
        $this->products->save($product);
    }

//    public function changeCategories($id, CategoriesForm $form): void
//    {
//        $product = $this->products->get($id);
//        $category = $this->categories->get($form->main);
//        $product->changeMainCategory($category->id);
//        $product->revokeCategories();
//        foreach ($form->others as $otherId) {
//            $category = $this->categories->get($otherId);
//            $product->assignCategory($category->id);
//        }
//        $this->products->save($product);
//    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $product = $this->products->get($id);
        foreach ($form->files as $file) {
            $product->addPhoto($file);
        }
        $this->products->save($product);
    }

    public function movePhotoUp($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoUp($photoId);
        $this->products->save($product);
    }

    public function movePhotoDown($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoDown($photoId);
        $this->products->save($product);
    }

    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }

    public function addRelatedProduct($id, $otherId): void
    {
        $product = $this->products->get($id);
        $other = $this->products->get($otherId);
        $product->assignRelatedProduct($other->id);
        $this->products->save($product);
    }

    public function removeRelatedProduct($id, $otherId): void
    {
        $product = $this->products->get($id);
        $other = $this->products->get($otherId);
        $product->revokeRelatedAssignment($other->id);
        $this->products->save($product);
    }

    public function addModification($id, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->addModification(
            $form->code,
            $form->name,
            $form->price,
            $form->quantity
        );
        $product->updateModificationsQty();
        $this->products->save($product);
    }

    public function editModification($id, $modificationId, ModificationForm $form): void
    {
        $product = $this->products->get($id);
        $product->editModification(
            $modificationId,
            $form->code,
            $form->name,
            $form->price,
            $form->quantity
        );
        $product->updateModificationsQty();
        $this->products->save($product);
    }

    public function changeModificationQty($id, $modificationId, int $qty): void
    {
        $product = $this->products->get($id);
        $product->changeModificationQty(
            $modificationId,
            $qty
        );
        $product->updateModificationsQty();
        $this->products->save($product);
    }

    public function removeModification($id, $modificationId): void
    {
        $product = $this->products->get($id);
        $product->removeModification($modificationId);
        $this->products->save($product);
    }

    public function changePrice($id, PriceForm $form): void
    {
        $product = $this->products->get($id);
        $product->setPrice($form->new, $form->old);
        $this->products->save($product);
    }

    public function remove($id): void
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
        $this->deleteDirectory($id);
    }

    public function deleteDirectory($id)
    {
        $this->removeDirectory(Yii::getAlias('@files') . '/products/' . $id . '/');
        $this->removeDirectory(Yii::getAlias('@files') . '/cache/products/' . $id . '/');
    }

    protected function removeDirectory($dir)
    {
        if (is_dir($dir)) {
            if ($objects = glob($dir . "/*")) {
                foreach ($objects as $obj) {
                    is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
                }
            }
            rmdir($dir);
        }
    }

    public function activate($id)
    {
        $product = $this->products->get($id);
        $product->activate();
        $product->save($product);
    }

    public function draft($id)
    {
        $product = $this->products->get($id);
        $product->draft();
        $product->save($product);
    }
}