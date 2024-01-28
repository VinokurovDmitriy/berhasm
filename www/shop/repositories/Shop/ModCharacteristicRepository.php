<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\ModCharacteristic;
use shop\repositories\NotFoundException;

class ModCharacteristicRepository
{
    public function get($id): ModCharacteristic
    {
        if (!$characteristic = ModCharacteristic::findOne($id)) {
            throw new NotFoundException('Entity is not found.');
        }
        return $characteristic;
    }

    public function save(ModCharacteristic $characteristic): void
    {
        if (!$characteristic->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove (ModCharacteristic $characteristic): void
    {
        if (!$characteristic->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}