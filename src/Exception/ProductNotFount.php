<?php

declare(strict_types=1);

namespace Terminal\Exception;

final class ProductNotFount extends \InvalidArgumentException
{
    protected $message = 'Product (%s) Not Found';

    public function __construct($productCode)
    {
        $this->message = sprintf($this->message, $productCode);

        parent::__construct($this->message);
    }
}