<?php

namespace App\DTOs\Mra;

class TaxRateDto
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $chargeMode = null;
    public ?int $ordinal = null;
    public ?float $rate = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id instanceof self ? $this->id->toArray() : $this->id,
            'name' => $this->name instanceof self ? $this->name->toArray() : $this->name,
            'chargeMode' => $this->chargeMode instanceof self ? $this->chargeMode->toArray() : $this->chargeMode,
            'ordinal' => $this->ordinal instanceof self ? $this->ordinal->toArray() : $this->ordinal,
            'rate' => $this->rate instanceof self ? $this->rate->toArray() : $this->rate,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('id', $data)) {
            $instance->id = $data['id'];
        }
        if (array_key_exists('name', $data)) {
            $instance->name = $data['name'];
        }
        if (array_key_exists('chargeMode', $data)) {
            $instance->chargeMode = $data['chargeMode'];
        }
        if (array_key_exists('ordinal', $data)) {
            $instance->ordinal = $data['ordinal'];
        }
        if (array_key_exists('rate', $data)) {
            $instance->rate = $data['rate'];
        }
        return $instance;
    }
}
