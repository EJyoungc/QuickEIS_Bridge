<?php

namespace App\DTOs\Mra;

class ProductState
{
    public ?string $productId = null;
    public ?string $description = null;
    public ?string $psCode = null;
    public ?string $psDescription = null;
    public ?array $applicableTaxRateIds = null;
    public ?array $quantitiesInStock = null;

    public function toArray(): array
    {
        return [
            'productId' => $this->productId instanceof self ? $this->productId->toArray() : $this->productId,
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
            'psCode' => $this->psCode instanceof self ? $this->psCode->toArray() : $this->psCode,
            'psDescription' => $this->psDescription instanceof self ? $this->psDescription->toArray() : $this->psDescription,
            'applicableTaxRateIds' => $this->applicableTaxRateIds instanceof self ? $this->applicableTaxRateIds->toArray() : $this->applicableTaxRateIds,
            'quantitiesInStock' => $this->quantitiesInStock instanceof self ? $this->quantitiesInStock->toArray() : $this->quantitiesInStock,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productId', $data)) {
            $instance->productId = $data['productId'];
        }
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        if (array_key_exists('psCode', $data)) {
            $instance->psCode = $data['psCode'];
        }
        if (array_key_exists('psDescription', $data)) {
            $instance->psDescription = $data['psDescription'];
        }
        if (array_key_exists('applicableTaxRateIds', $data)) {
            $instance->applicableTaxRateIds = $data['applicableTaxRateIds'];
        }
        if (array_key_exists('quantitiesInStock', $data)) {
            $instance->quantitiesInStock = $data['quantitiesInStock'];
        }
        return $instance;
    }
}
