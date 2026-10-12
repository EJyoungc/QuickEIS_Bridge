<?php

namespace App\DTOs\Mra;

class TinAuthorizationRequirementRequest
{
    public ?string $tin = null;

    public function toArray(): array
    {
        return [
            'tin' => $this->tin instanceof self ? $this->tin->toArray() : $this->tin,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('tin', $data)) {
            $instance->tin = $data['tin'];
        }
        return $instance;
    }
}
