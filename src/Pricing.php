<?php

declare(strict_types=1);

namespace Terminal;

final class Pricing
{
    private int $units;
    private float $price;

    public function __construct(int $units, float $price)
    {
        $this->units = $units;
        $this->price = $price;
    }

    public static function create(int $units, float $price): self
    {
        return new self($units, $price);
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
