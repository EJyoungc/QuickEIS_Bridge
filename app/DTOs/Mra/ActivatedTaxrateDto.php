<?php

namespace App\DTOs\Mra;

class ActivatedTaxrateDto
{
    public ?int $id = null;
    public ?int $taxpayerConfigurationId = null;
    public ?string $taxRateId = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id instanceof self ? $this->id->toArray() : $this->id,
            'taxpayerConfigurationId' => $this->taxpayerConfigurationId instanceof self ? $this->taxpayerConfigurationId->toArray() : $this->taxpayerConfigurationId,
            'taxRateId' => $this->taxRateId instanceof self ? $this->taxRateId->toArray() : $this->taxRateId,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('id', $data)) {
            $instance->id = $data['id'];
        }
        if (array_key_exists('taxpayerConfigurationId', $data)) {
            $instance->taxpayerConfigurationId = $data['taxpayerConfigurationId'];
        }
        if (array_key_exists('taxRateId', $data)) {
            $instance->taxRateId = $data['taxRateId'];
        }
        return $instance;
    }
}
