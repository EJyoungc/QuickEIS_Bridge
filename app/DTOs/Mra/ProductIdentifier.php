<?php

namespace App\DTOs\Mra;

class ProductIdentifier
{
    public ?string $productId = null;
    public ?string $tin = null;

    public function toArray(): array
    {
        return [
            'productId' => $this->productId instanceof self ? $this->productId->toArray() : $this->productId,
            'tin' => $this->tin instanceof self ? $this->tin->toArray() : $this->tin,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productId', $data)) {
            $instance->productId = $data['productId'];
        }
        if (array_key_exists('tin', $data)) {
            $instance->tin = $data['tin'];
        }
        return $instance;
    }
}
