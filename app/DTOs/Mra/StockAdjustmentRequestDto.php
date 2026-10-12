<?php

namespace App\DTOs\Mra;

class StockAdjustmentRequestDto
{
    public ?string $barcode = null;
    public ?float $quantity = null;
    public ?string $adjustmentReason = null;
    public ?string $adjustmentType = null;
    public ?string $siteId = null;
    public ?string $taxpayerRemarks = null;

    public function toArray(): array
    {
        return [
            'barcode' => $this->barcode instanceof self ? $this->barcode->toArray() : $this->barcode,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'adjustmentReason' => $this->adjustmentReason instanceof self ? $this->adjustmentReason->toArray() : $this->adjustmentReason,
            'adjustmentType' => $this->adjustmentType instanceof self ? $this->adjustmentType->toArray() : $this->adjustmentType,
            'siteId' => $this->siteId instanceof self ? $this->siteId->toArray() : $this->siteId,
            'taxpayerRemarks' => $this->taxpayerRemarks instanceof self ? $this->taxpayerRemarks->toArray() : $this->taxpayerRemarks,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('barcode', $data)) {
            $instance->barcode = $data['barcode'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('adjustmentReason', $data)) {
            $instance->adjustmentReason = $data['adjustmentReason'];
        }
        if (array_key_exists('adjustmentType', $data)) {
            $instance->adjustmentType = $data['adjustmentType'];
        }
        if (array_key_exists('siteId', $data)) {
            $instance->siteId = $data['siteId'];
        }
        if (array_key_exists('taxpayerRemarks', $data)) {
            $instance->taxpayerRemarks = $data['taxpayerRemarks'];
        }
        return $instance;
    }
}
