<?php

namespace App\DTOs\Mra;

class UnitOfMeasureDto
{
    public ?string $unitOfMeasure = null;
    public ?string $unitOfMeasureDescription = null;

    public function toArray(): array
    {
        return [
            'unitOfMeasure' => $this->unitOfMeasure instanceof self ? $this->unitOfMeasure->toArray() : $this->unitOfMeasure,
            'unitOfMeasureDescription' => $this->unitOfMeasureDescription instanceof self ? $this->unitOfMeasureDescription->toArray() : $this->unitOfMeasureDescription,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('unitOfMeasure', $data)) {
            $instance->unitOfMeasure = $data['unitOfMeasure'];
        }
        if (array_key_exists('unitOfMeasureDescription', $data)) {
            $instance->unitOfMeasureDescription = $data['unitOfMeasureDescription'];
        }
        return $instance;
    }
}
