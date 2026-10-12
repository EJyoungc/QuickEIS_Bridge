<?php

namespace App\DTOs\Mra;

class AdjustmentReasonDto
{
    public ?string $description = null;

    public function toArray(): array
    {
        return [
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        return $instance;
    }
}
