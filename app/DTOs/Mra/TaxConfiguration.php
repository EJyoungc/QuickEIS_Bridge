<?php

namespace App\DTOs\Mra;

class TaxConfiguration
{
    public ?int $id = null;
    public ?float $versionNo = null;
    public ?array $taxrates = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id instanceof self ? $this->id->toArray() : $this->id,
            'versionNo' => $this->versionNo instanceof self ? $this->versionNo->toArray() : $this->versionNo,
            'taxrates' => $this->taxrates instanceof self ? $this->taxrates->toArray() : $this->taxrates,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('id', $data)) {
            $instance->id = $data['id'];
        }
        if (array_key_exists('versionNo', $data)) {
            $instance->versionNo = $data['versionNo'];
        }
        if (array_key_exists('taxrates', $data)) {
            $instance->taxrates = $data['taxrates'];
        }
        return $instance;
    }
}
