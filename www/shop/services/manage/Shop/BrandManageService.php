<?php

namespace shop\services\manage\Shop;

use shop\entities\Meta;
use shop\entities\Shop\Brand;
use shop\forms\manage\Shop\BrandForm;
use shop\repositories\Shop\ProductRepository;
use shop\repositories\Shop\BrandsRepository;

class BrandManageService
{
    private $brands;
    private $products;

    public function __construct(BrandsRepository $brands, ProductRepository $products)
    {
        $this->brands = $brands;
        $this->products = $products;
    }

    public function create(BrandForm $form): Brand
    {
        $brand = Brand::create(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            ),
            $form->file,
            $form->country,
            $form->description
        );
        $this->brands->save($brand);
        return $brand;
    }

    public function edit($id, BrandForm $form): void
    {
        $brand = $this->brands->get($id);
        $brand->edit(
            $form->name,
            $form->slug,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            ),
            $form->file,
            $form->country,
            $form->description
        );
        $this->brands->save($brand);
    }

    public function remove($id): void
    {
        $brand = $this->brands->get($id);
        if ($this->products->existByBrand($brand->id)){
            throw new \DomainException('Unable to remove brand with products.');
        }
        if ($brand->file) {
            $file = \Yii::getAlias('@files') . '/brands/' . $brand->id . '-' . $brand->file;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $this->brands->remove($brand);
    }
}