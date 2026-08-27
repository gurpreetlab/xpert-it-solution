<?php

namespace App\Support\Cart;

final class CartActionResult
{
    private function __construct(
        public readonly bool $blocked,
        public readonly ?string $message = null,
    ) {}

    public static function ok(): self
    {
        return new self(blocked: false);
    }

    public static function blocked(string $message): self
    {
        return new self(blocked: true, message: $message);
    }
}
