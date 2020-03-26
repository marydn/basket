<?php

declare(strict_types=1);

namespace Terminal;

use Terminal\Shared\Collection;

final class Basket extends Collection
{
    protected function getType(): string
    {
        return Product::class;
    }

    public function add(Product $product): self
    {
        $this->items[] = $product;

        return $this;
    }
}
