<?php

namespace App\DTOs\Mra;

class InventoryRequest
{
    public ?string $tin = null;
    public ?string $siteId = null;

    public function toArray(): array
    {
        return [
            'tin' => $this->tin instanceof self ? $this->tin->toArray() : $this->tin,
            'siteId' => $this->siteId instanceof self ? $this->siteId->toArray() : $this->siteId,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('tin', $data)) {
            $instance->tin = $data['tin'];
        }
        if (array_key_exists('siteId', $data)) {
            $instance->siteId = $data['siteId'];
        }
        return $instance;
    }
}
