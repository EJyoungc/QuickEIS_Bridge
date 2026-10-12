<?php

namespace App\DTOs\Mra;

class TaxOfficeDto
{
    public ?string $code = null;
    public ?string $name = null;

    public function toArray(): array
    {
        return [
            'code' => $this->code instanceof self ? $this->code->toArray() : $this->code,
            'name' => $this->name instanceof self ? $this->name->toArray() : $this->name,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('code', $data)) {
            $instance->code = $data['code'];
        }
        if (array_key_exists('name', $data)) {
            $instance->name = $data['name'];
        }
        return $instance;
    }
}
