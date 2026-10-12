<?php

namespace App\DTOs\Mra;

class AddProductApiResponseDto
{
    public ?int $productId = null;
    public ?string $barcode = null;
    public ?string $hsCode = null;
    public ?string $taxRateId = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $uom = null;

    public function toArray(): array
    {
        return [
            'productId' => $this->productId instanceof self ? $this->productId->toArray() : $this->productId,
            'barcode' => $this->barcode instanceof self ? $this->barcode->toArray() : $this->barcode,
            'hsCode' => $this->hsCode instanceof self ? $this->hsCode->toArray() : $this->hsCode,
            'taxRateId' => $this->taxRateId instanceof self ? $this->taxRateId->toArray() : $this->taxRateId,
            'name' => $this->name instanceof self ? $this->name->toArray() : $this->name,
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
            'uom' => $this->uom instanceof self ? $this->uom->toArray() : $this->uom,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productId', $data)) {
            $instance->productId = $data['productId'];
        }
        if (array_key_exists('barcode', $data)) {
            $instance->barcode = $data['barcode'];
        }
        if (array_key_exists('hsCode', $data)) {
            $instance->hsCode = $data['hsCode'];
        }
        if (array_key_exists('taxRateId', $data)) {
            $instance->taxRateId = $data['taxRateId'];
        }
        if (array_key_exists('name', $data)) {
            $instance->name = $data['name'];
        }
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        if (array_key_exists('uom', $data)) {
            $instance->uom = $data['uom'];
        }
        return $instance;
    }
}
