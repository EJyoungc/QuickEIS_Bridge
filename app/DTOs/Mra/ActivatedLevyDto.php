<?php

namespace App\DTOs\Mra;

class ActivatedLevyDto
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $chargeMode = null;
    public ?float $rate = null;
    public ?bool $isActive = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id instanceof self ? $this->id->toArray() : $this->id,
            'name' => $this->name instanceof self ? $this->name->toArray() : $this->name,
            'chargeMode' => $this->chargeMode instanceof self ? $this->chargeMode->toArray() : $this->chargeMode,
            'rate' => $this->rate instanceof self ? $this->rate->toArray() : $this->rate,
            'isActive' => $this->isActive instanceof self ? $this->isActive->toArray() : $this->isActive,
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
        if (array_key_exists('rate', $data)) {
            $instance->rate = $data['rate'];
        }
        if (array_key_exists('isActive', $data)) {
            $instance->isActive = $data['isActive'];
        }
        return $instance;
    }
}
