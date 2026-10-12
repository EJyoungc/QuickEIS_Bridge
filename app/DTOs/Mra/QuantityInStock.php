<?php

namespace App\DTOs\Mra;

class QuantityInStock
{
    public ?string $locationId = null;
    public ?string $locationName = null;
    public ?string $uom = null;
    public ?float $quantity = null;

    public function toArray(): array
    {
        return [
            'locationId' => $this->locationId instanceof self ? $this->locationId->toArray() : $this->locationId,
            'locationName' => $this->locationName instanceof self ? $this->locationName->toArray() : $this->locationName,
            'uom' => $this->uom instanceof self ? $this->uom->toArray() : $this->uom,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('locationId', $data)) {
            $instance->locationId = $data['locationId'];
        }
        if (array_key_exists('locationName', $data)) {
            $instance->locationName = $data['locationName'];
        }
        if (array_key_exists('uom', $data)) {
            $instance->uom = $data['uom'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        return $instance;
    }
}
