<?php

namespace shop\cart;

use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Product;

class CartItem
{
    public $product;
    public $modificationId;
    public $quantity;

    public function __construct(Product $product, int $modificationId = null, int $quantity)
    {
        $this->product = $product;
        $this->modificationId = $modificationId;
        $this->quantity = $quantity;
    }

    public function getId()
    {
        return md5(serialize([$this->product->id, $this->modificationId]));
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getProductId(): int
    {
        return $this->product->id;
    }

    public function getModificationId(): ?int
    {
        return $this->modificationId;
    }

    public function getModification(): Modification
    {
        if ($this->modificationId) {
            return $this->product->getModification($this->modificationId);
        }
        return null;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        if ($this->modificationId) {
            return $this->product->getModificationPrice($this->modificationId);
        }
        return $this->product->price_new;
    }

    public function getCost(): float
    {
        return $this->getPrice() * $this->quantity;
    }

    public function addQuantity($quantity): self
    {
        return new static($this->product, $this->modificationId, $this->quantity += $quantity);
    }

    public function changeQuantity($quantity)
    {
        return new static($this->product, $this->modificationId, $quantity);
    }

}