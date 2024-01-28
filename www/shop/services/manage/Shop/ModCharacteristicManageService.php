<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\ModCharacteristic;
use shop\forms\manage\Shop\ModCharacteristicForm;
use shop\repositories\Shop\ModCharacteristicRepository;
use shop\repositories\Shop\CategoryRepository;

class ModCharacteristicManageService
{
    private $characteristics;
    private $categories;

    public function __construct(
        ModCharacteristicRepository $characteristics,
        CategoryRepository $categories
    )
    {
        $this->characteristics = $characteristics;
        $this->categories = $categories;
    }

    public function create(ModCharacteristicForm $form): ModCharacteristic
    {
        $characteristic = ModCharacteristic::create(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->ord,
            $form->category_id
        );
        $this->characteristics->save($characteristic);
        return $characteristic;
    }

    public function edit($id, ModCharacteristicForm $form): void
    {
        $characteristic = $this->characteristics->get($id);
        $characteristic->edit(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->ord,
            $form->category_id
        );
        $this->characteristics->save($characteristic);
    }

    public function remove($id): void
    {
        $characteristic = $this->characteristics->get($id);
        $this->characteristics->remove($characteristic);
    }
}