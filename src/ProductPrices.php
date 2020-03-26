<?php

declare(strict_types=1);

namespace Terminal;

use Terminal\Shared\Collection;

final class ProductPrices extends Collection
{
    protected function getType(): string
    {
        return Pricing::class;
    }

    public function add(Pricing $pricing): self
    {
        if (!$this->contains($pricing)) {
            $this->items[] = $pricing;
        }

        return $this;
    }
}
