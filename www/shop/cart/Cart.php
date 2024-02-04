<?php

namespace shop\cart;

use shop\cart\cost\calculator\CalculatorInterface;
use shop\cart\cost\Cost;
use shop\cart\storage\StorageInterface;

class Cart
{
    /* @var $items CartItem[] */
    public $items;

    private $storage;
    private $calculator;

    public function __construct(StorageInterface $storage, CalculatorInterface $calculator)
    {
        $this->storage = $storage;
        $this->calculator = $calculator;
    }

    public function getItems(): array
    {
        $this->loadItems();
        return $this->items;
    }

    public function getAmount()
    {
        $this->loadItems();
        return count($this->items);
    }

    public function add(CartItem $item)
    {
        $this->loadItems();

        foreach ($this->items as $i => $current) {
            if ($current->getId() == $item->getId()) {
                if ($this->items[$i]->getProduct()->canBeCheckout($this->items[$i]->getModificationId() ?: null, ($current->getQuantity() + $item->getQuantity()))) {
                    $this->items[$i] = $current->addQuantity($item->getQuantity());
                    $this->saveItems();
                }
                return;
            }
        }
        if ($item->getProduct()->canBeCheckout($item->getModificationId() ?: null, $item->getQuantity())) {
            $this->items[] = $item;
            $this->saveItems();
        }
    }

    public function set($id, $quantity): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() == $id) {
                $this->items[$i] = $current->changeQuantity($quantity);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function changeQuantity($id, $quantity)
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() == $id) {
                if ($current->getQuantity() == 1 && $quantity == -1) {
                    return;
                }
                if ($this->items[$i]->getProduct()->canBeCheckout($this->items[$i]->getModificationId() ?: null, ($current->getQuantity() + $quantity))) {
                    $this->items[$i] = $current->addQuantity($quantity);
                    $this->saveItems();
                }
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function remove($id): void
    {
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() == $id) {
                unset($this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function clear(): void
    {
        $this->items = [];
        $this->saveItems();
    }

    public function getCost(): Cost
    {
        $this->loadItems();
        return $this->calculator->getCost($this->items);
    }

    private function loadItems()
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    private function saveItems()
    {
        $this->storage->save($this->items);
    }
}