<?php

declare(strict_types=1);

namespace Terminal;

use Terminal\ValueObject\ProductCode;

final class Terminal
{
    private Inventory $inventory;
    private Basket $basket;

    public function __construct()
    {
        $this->inventory = new Inventory();
        $this->basket    = new Basket();
    }

    public function addProductToInventory(Product $product): self
    {
        $this->inventory->add($product);

        return $this;
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function scanItem(ProductCode $productCode): self
    {
        $product = $this->inventory->getProductByCode($productCode);

        $this->basket->add($product);

        return $this;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function getTotal(): float
    {
        $codesInBasket = [];

        /** @var Product $item */
        foreach ($this->basket as $item) {
            $code = $item->getCode()->value();
            if (isset($codesInBasket[$code])) {
                $codesInBasket[$code] = $codesInBasket[$code] + 1;
            } else {
                $codesInBasket[$code] = 1;
            }
        }

        $total = 0;
        foreach ($codesInBasket as $code => $units) {
            $product = $this->inventory->getProductByCode(ProductCode::create($code));

            $price = $product->getPriceByUnits($units);

            $total += $price;
        }

        return $total;
    }
}
