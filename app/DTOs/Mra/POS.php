<?php

namespace App\DTOs\Mra;

class POS
{
    public ?string $productID = null;
    public ?string $productVersion = null;

    public function toArray(): array
    {
        return [
            'productID' => $this->productID instanceof self ? $this->productID->toArray() : $this->productID,
            'productVersion' => $this->productVersion instanceof self ? $this->productVersion->toArray() : $this->productVersion,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productID', $data)) {
            $instance->productID = $data['productID'];
        }
        if (array_key_exists('productVersion', $data)) {
            $instance->productVersion = $data['productVersion'];
        }
        return $instance;
    }
}
