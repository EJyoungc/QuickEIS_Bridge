<?php

namespace App\DTOs\Mra;

class RawMaterial
{
    public ?string $productId = null;
    public ?string $productName = null;
    public ?float $availableQuantity = null;
    public ?float $usedQuantity = null;

    public function toArray(): array
    {
        return [
            'productId' => $this->productId instanceof self ? $this->productId->toArray() : $this->productId,
            'productName' => $this->productName instanceof self ? $this->productName->toArray() : $this->productName,
            'availableQuantity' => $this->availableQuantity instanceof self ? $this->availableQuantity->toArray() : $this->availableQuantity,
            'usedQuantity' => $this->usedQuantity instanceof self ? $this->usedQuantity->toArray() : $this->usedQuantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productId', $data)) {
            $instance->productId = $data['productId'];
        }
        if (array_key_exists('productName', $data)) {
            $instance->productName = $data['productName'];
        }
        if (array_key_exists('availableQuantity', $data)) {
            $instance->availableQuantity = $data['availableQuantity'];
        }
        if (array_key_exists('usedQuantity', $data)) {
            $instance->usedQuantity = $data['usedQuantity'];
        }
        return $instance;
    }
}
