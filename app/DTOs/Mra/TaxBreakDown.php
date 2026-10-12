<?php

namespace App\DTOs\Mra;

class TaxBreakDown
{
    public ?string $rateId = null;
    public ?float $taxableAmount = null;
    public ?float $taxAmount = null;

    public function toArray(): array
    {
        return [
            'rateId' => $this->rateId instanceof self ? $this->rateId->toArray() : $this->rateId,
            'taxableAmount' => $this->taxableAmount instanceof self ? $this->taxableAmount->toArray() : $this->taxableAmount,
            'taxAmount' => $this->taxAmount instanceof self ? $this->taxAmount->toArray() : $this->taxAmount,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('rateId', $data)) {
            $instance->rateId = $data['rateId'];
        }
        if (array_key_exists('taxableAmount', $data)) {
            $instance->taxableAmount = $data['taxableAmount'];
        }
        if (array_key_exists('taxAmount', $data)) {
            $instance->taxAmount = $data['taxAmount'];
        }
        return $instance;
    }
}
