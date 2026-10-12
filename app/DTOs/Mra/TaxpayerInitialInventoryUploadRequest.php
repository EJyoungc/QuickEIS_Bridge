<?php

namespace App\DTOs\Mra;

class TaxpayerInitialInventoryUploadRequest
{
    public ?string $tin = null;
    public ?bool $isLastBatch = null;
    public ?array $products = null;

    public function toArray(): array
    {
        return [
            'tin' => $this->tin instanceof self ? $this->tin->toArray() : $this->tin,
            'isLastBatch' => $this->isLastBatch instanceof self ? $this->isLastBatch->toArray() : $this->isLastBatch,
            'products' => $this->products instanceof self ? $this->products->toArray() : $this->products,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('tin', $data)) {
            $instance->tin = $data['tin'];
        }
        if (array_key_exists('isLastBatch', $data)) {
            $instance->isLastBatch = $data['isLastBatch'];
        }
        if (array_key_exists('products', $data)) {
            $instance->products = $data['products'];
        }
        return $instance;
    }
}
