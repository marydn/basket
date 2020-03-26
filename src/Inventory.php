<?php

declare(strict_types=1);

namespace Terminal;

use Terminal\Exception\ProductNotFount;
use Terminal\Shared\Collection;
use Terminal\ValueObject\ProductCode;

final class Inventory extends Collection
{
    protected function getType(): string
    {
        return Product::class;
    }

    public function add(Product $product): self
    {
        if (!$this->contains($product)) {
            $this->items[] = $product;
        }

        return $this;
    }

    public function get(Product $product)
    {
        if (!$this->contains($product)) {
            throw new ProductNotFount($product);
        }

        $key = \array_search($product, $this->items);

        return $this->items[$key];
    }

    public function getProductByCode(ProductCode $productCode): Product
    {
        $filtered = \array_filter($this->items, function(Product $product) use ($productCode) {
            return $product->getCode()->value() === $productCode->value();
        });

        if (!count($filtered)) {
            throw new ProductNotFount($productCode->value());
        }

        return $filtered[\array_key_first($filtered)];
    }
}