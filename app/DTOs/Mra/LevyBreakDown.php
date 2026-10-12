<?php

namespace App\DTOs\Mra;

class LevyBreakDown
{
    public ?string $levyTypeId = null;
    public ?float $levyRate = null;
    public ?float $levyAmount = null;

    public function toArray(): array
    {
        return [
            'levyTypeId' => $this->levyTypeId instanceof self ? $this->levyTypeId->toArray() : $this->levyTypeId,
            'levyRate' => $this->levyRate instanceof self ? $this->levyRate->toArray() : $this->levyRate,
            'levyAmount' => $this->levyAmount instanceof self ? $this->levyAmount->toArray() : $this->levyAmount,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('levyTypeId', $data)) {
            $instance->levyTypeId = $data['levyTypeId'];
        }
        if (array_key_exists('levyRate', $data)) {
            $instance->levyRate = $data['levyRate'];
        }
        if (array_key_exists('levyAmount', $data)) {
            $instance->levyAmount = $data['levyAmount'];
        }
        return $instance;
    }
}
