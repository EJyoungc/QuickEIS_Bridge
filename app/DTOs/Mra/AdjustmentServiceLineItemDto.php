<?php

namespace App\DTOs\Mra;

class AdjustmentServiceLineItemDto
{
    public ?string $serviceName = null;
    public ?float $unitPrice = null;
    public ?float $quantity = null;
    public ?string $taxRateId = null;

    public function toArray(): array
    {
        return [
            'serviceName' => $this->serviceName instanceof self ? $this->serviceName->toArray() : $this->serviceName,
            'unitPrice' => $this->unitPrice instanceof self ? $this->unitPrice->toArray() : $this->unitPrice,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'taxRateId' => $this->taxRateId instanceof self ? $this->taxRateId->toArray() : $this->taxRateId,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('serviceName', $data)) {
            $instance->serviceName = $data['serviceName'];
        }
        if (array_key_exists('unitPrice', $data)) {
            $instance->unitPrice = $data['unitPrice'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('taxRateId', $data)) {
            $instance->taxRateId = $data['taxRateId'];
        }
        return $instance;
    }
}
