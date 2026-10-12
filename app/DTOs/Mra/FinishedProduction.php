<?php

namespace App\DTOs\Mra;

class FinishedProduction
{
    public ?float $quantity = null;
    public ?string $unitOfMeasure = null;
    public ?string $expiryDate = null;
    public ?string $productDescription = null;
    public ?string $barcode = null;

    public function toArray(): array
    {
        return [
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'unitOfMeasure' => $this->unitOfMeasure instanceof self ? $this->unitOfMeasure->toArray() : $this->unitOfMeasure,
            'expiryDate' => $this->expiryDate instanceof self ? $this->expiryDate->toArray() : $this->expiryDate,
            'productDescription' => $this->productDescription instanceof self ? $this->productDescription->toArray() : $this->productDescription,
            'barcode' => $this->barcode instanceof self ? $this->barcode->toArray() : $this->barcode,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('unitOfMeasure', $data)) {
            $instance->unitOfMeasure = $data['unitOfMeasure'];
        }
        if (array_key_exists('expiryDate', $data)) {
            $instance->expiryDate = $data['expiryDate'];
        }
        if (array_key_exists('productDescription', $data)) {
            $instance->productDescription = $data['productDescription'];
        }
        if (array_key_exists('barcode', $data)) {
            $instance->barcode = $data['barcode'];
        }
        return $instance;
    }
}
