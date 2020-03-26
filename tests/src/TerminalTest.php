<?php

namespace Terminal\Test;

use PHPUnit\Framework\TestCase;
use Terminal\Pricing;
use Terminal\Product;
use Terminal\Terminal;
use Terminal\ValueObject\ProductCode;

class TerminalTest extends TestCase
{
    private Terminal $terminal;

    public function setUp(): void
    {
        $this->terminal = new Terminal();

        $product1 = Product::create(ProductCode::create('ZA'), 2);
        $product2 = Product::create(ProductCode::create('YB'), 12);
        $product3 = Product::create(ProductCode::create('FC'), 1.25);
        $product4 = Product::create(ProductCode::create('GD'), 0.15);

        // bulk prices
        $product1->addPrice(Pricing::create(4, 7));
        $product3->addPrice(Pricing::create(6, 6));

        $this->terminal
            ->addProductToInventory($product1)
            ->addProductToInventory($product2)
            ->addProductToInventory($product3)
            ->addProductToInventory($product4)
        ;
    }

    /** @test */
    public function it_should_have_four_products(): void
    {
        $this->assertCount(4, $this->terminal->getInventory());
    }

    /** @test */
    public function it_should_have_two_prices_for_product_one(): void
    {
        $productCode = ProductCode::create('ZA');

        $inventory = $this->terminal->getInventory();

        $this->assertCount(2, $inventory->getProductByCode($productCode)->getPrices());
    }

    /** @test */
    public function it_should_have_two_correct_prices(): void
    {
        $productCode = ProductCode::create('ZA');
        $inventory   = $this->terminal->getInventory();
        $product     = $inventory->getProductByCode($productCode);

        $this->assertEquals(4.0, $product->getPriceByUnits(2));
        $this->assertEquals(7.0, $product->getPriceByUnits(4));
    }

    /** @test */
    public function it_should_add_a_product_to_basket(): void
    {
        $productCode = ProductCode::create('ZA');

        $this->terminal->scanItem($productCode);

        $this->assertCount(1, $this->terminal->getBasket());
    }

    /** @test */
    public function it_should_add_two_products_to_basket(): void
    {
        $productCode = ProductCode::create('ZA');

        $this->terminal->scanItem($productCode);
        $this->terminal->scanItem($productCode);

        $this->assertCount(2, $this->terminal->getBasket());
    }

    /** @test */
    public function it_should_test_total_for_one_product(): void
    {
        $productCode = ProductCode::create('ZA');

        $this->terminal->scanItem($productCode);

        $this->assertEquals(2, $this->terminal->getTotal());
    }

    /** @test */
    public function it_should_test_total_for_bulk(): void
    {
        $productCode = ProductCode::create('ZA');

        $this->terminal->scanItem($productCode);
        $this->terminal->scanItem($productCode);
        $this->terminal->scanItem($productCode);
        $this->terminal->scanItem($productCode);

        $this->assertEquals(7, $this->terminal->getTotal());
    }

    /** @test */
    public function it_should_test_basket_for_case_one(): void
    {
        $productCodes = 'YB,FC,GD,ZA,YB,ZA,ZA,ZA';
        $productCodes = \explode(',', $productCodes);

        foreach ($productCodes as $productCode) {
            $this->terminal->scanItem(ProductCode::create($productCode));
        }

        $total = $this->terminal->getTotal();

        $this->assertCount(8, $this->terminal->getBasket());
        $this->assertEquals(32.4, $total);
    }

    /** @test */
    public function it_should_test_basket_for_case_two(): void
    {
        $productCodes = 'FC,FC,FC,FC,FC,FC,FC';
        $productCodes = \explode(',', $productCodes);

        foreach ($productCodes as $productCode) {
            $this->terminal->scanItem(ProductCode::create($productCode));
        }

        $total = $this->terminal->getTotal();

        $this->assertEquals(7.25, $total);
    }

    /** @test */
    public function it_should_test_basket_for_case_three(): void
    {
        $productCodes = 'ZA,YB,FC,GD';
        $productCodes = \explode(',', $productCodes);

        foreach ($productCodes as $productCode) {
            $this->terminal->scanItem(ProductCode::create($productCode));
        }

        $total = $this->terminal->getTotal();

        $this->assertEquals(15.40, $total);
    }
}
