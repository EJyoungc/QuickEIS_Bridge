<?php

namespace App\DTOs\Mra;

class HsCodeLookupDto
{
    public ?string $code = null;
    public ?string $description = null;
    public ?string $taxRateId = null;

    public function toArray(): array
    {
        return [
            'code' => $this->code instanceof self ? $this->code->toArray() : $this->code,
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
            'taxRateId' => $this->taxRateId instanceof self ? $this->taxRateId->toArray() : $this->taxRateId,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('code', $data)) {
            $instance->code = $data['code'];
        }
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        if (array_key_exists('taxRateId', $data)) {
            $instance->taxRateId = $data['taxRateId'];
        }
        return $instance;
    }
}
