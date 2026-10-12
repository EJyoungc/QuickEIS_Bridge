<?php

namespace App\DTOs\Mra;

class InventoryTransferItem
{
    public ?string $barcode = null;
    public ?float $quantity = null;
    public ?float $price = null;

    public function toArray(): array
    {
        return [
            'barcode' => $this->barcode instanceof self ? $this->barcode->toArray() : $this->barcode,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'price' => $this->price instanceof self ? $this->price->toArray() : $this->price,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('barcode', $data)) {
            $instance->barcode = $data['barcode'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('price', $data)) {
            $instance->price = $data['price'];
        }
        return $instance;
    }
}
