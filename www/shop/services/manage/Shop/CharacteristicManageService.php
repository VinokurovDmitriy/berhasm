<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\Characteristic;
use shop\forms\manage\Shop\CharacteristicForm;
use shop\repositories\Shop\CharacteristicRepository;
use shop\repositories\Shop\CategoryRepository;

class CharacteristicManageService
{
    private $characteristics;
    private $categories;

    public function __construct(
        CharacteristicRepository $characteristics,
        CategoryRepository $categories
    )
    {
        $this->characteristics = $characteristics;
        $this->categories = $categories;
    }

    public function create(CharacteristicForm $form): Characteristic
    {
        $characteristic = Characteristic::create(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->ord,
            $form->category_id,
            $form->main
        );
        $this->characteristics->save($characteristic);
        return $characteristic;
    }

    public function edit($id, CharacteristicForm $form): void
    {
        $characteristic = $this->characteristics->get($id);
        $characteristic->edit(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->ord,
            $form->category_id,
            $form->main
        );
        $this->characteristics->save($characteristic);
    }

    public function remove($id): void
    {
        $characteristic = $this->characteristics->get($id);
        $this->characteristics->remove($characteristic);
    }
}