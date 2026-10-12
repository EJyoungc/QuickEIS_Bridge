<?php

namespace App\DTOs\Mra;

class WarehouseInventoryItemDto
{
    public ?string $barcode = null;
    public ?string $productName = null;
    public ?string $productDescription = null;
    public ?float $currentQuantity = null;
    public ?string $uom = null;
    public ?float $price = null;

    public function toArray(): array
    {
        return [
            'barcode' => $this->barcode instanceof self ? $this->barcode->toArray() : $this->barcode,
            'productName' => $this->productName instanceof self ? $this->productName->toArray() : $this->productName,
            'productDescription' => $this->productDescription instanceof self ? $this->productDescription->toArray() : $this->productDescription,
            'currentQuantity' => $this->currentQuantity instanceof self ? $this->currentQuantity->toArray() : $this->currentQuantity,
            'uom' => $this->uom instanceof self ? $this->uom->toArray() : $this->uom,
            'price' => $this->price instanceof self ? $this->price->toArray() : $this->price,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('barcode', $data)) {
            $instance->barcode = $data['barcode'];
        }
        if (array_key_exists('productName', $data)) {
            $instance->productName = $data['productName'];
        }
        if (array_key_exists('productDescription', $data)) {
            $instance->productDescription = $data['productDescription'];
        }
        if (array_key_exists('currentQuantity', $data)) {
            $instance->currentQuantity = $data['currentQuantity'];
        }
        if (array_key_exists('uom', $data)) {
            $instance->uom = $data['uom'];
        }
        if (array_key_exists('price', $data)) {
            $instance->price = $data['price'];
        }
        return $instance;
    }
}
