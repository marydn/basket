<?php

declare(strict_types=1);

namespace Terminal\ValueObject;

final class ProductCode
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function create(string $code): self
    {
        return new self($code);
    }

    public function value(): string
    {
        return $this->code;
    }
    public function __toString()
    {
        return $this->code;
    }
}