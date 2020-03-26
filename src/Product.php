<?php

declare(strict_types=1);

namespace Terminal;

use Terminal\ValueObject\ProductCode;

final class Product
{
    private ProductCode $productCode;
    private ProductPrices $prices;

    private function __construct(ProductCode $productCode, float $price, int $units = 1)
    {
        $this->productCode = $productCode;
        $this->prices      = new ProductPrices;

        // Add initial price
        $this->prices->add(Pricing::create($units, $price));
    }

    public static function create(ProductCode $productCode, float $price, int $units = 1)
    {
        return new self($productCode, $price, $units);
    }

    public function addPrice(Pricing $pricing): self
    {
        $this->prices->add($pricing);

        return $this;
    }

    public function getPrices(): ProductPrices
    {
        return $this->prices;
    }

    public function getPriceByUnits(int $units = 1): float
    {
        if ($units > 1) {
            $findBulkPrice = \array_filter($this->prices->toArray(), function(Pricing $price) use ($units) {
                return $price->getUnits() <= $units && $price->getUnits() !== 1;
            });

            if (\count($findBulkPrice)) {
                $price     = 0;
                $leftUnits = $units;

                /** @var Pricing $bulkPrice */
                foreach ($findBulkPrice as $bulkPrice) {
                    if ($units >= $bulkPrice->getUnits()) {
                        $price = $bulkPrice->getPrice();
                        $leftUnits = $leftUnits - $bulkPrice->getUnits();
                    } else {
                        $price = $bulkPrice->getPrice();
                    }
                }

                if ($leftUnits > 0) {
                    $pricePerUnit = $this->getPriceByUnits(1);
                    $price = $price + ($leftUnits * $pricePerUnit);
                }

                return $price;
            }
        }

        $findUnitPrice = \array_filter($this->prices->toArray(), function(Pricing $price) use ($units) {
            return $price->getUnits() === 1;
        });

        if (!count($findUnitPrice)) {
            throw new \InvalidArgumentException;
        }

        /** @var Pricing $price */
        $price = $findUnitPrice[\array_key_first($findUnitPrice)];

        return $price->getPrice() * $units;
    }

    public function getCode(): ProductCode
    {
        return $this->productCode;
    }
}
