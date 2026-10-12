<?php

namespace App\DTOs\Mra;

class LineItemDto
{
    public ?int $id = null;
    public ?string $productCode = null;
    public ?string $description = null;
    public ?float $unitPrice = null;
    public ?float $quantity = null;
    public ?float $discount = null;
    public ?float $total = null;
    public ?float $totalVAT = null;
    public ?string $taxRateId = null;
    public ?bool $isProduct = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id instanceof self ? $this->id->toArray() : $this->id,
            'productCode' => $this->productCode instanceof self ? $this->productCode->toArray() : $this->productCode,
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
            'unitPrice' => $this->unitPrice instanceof self ? $this->unitPrice->toArray() : $this->unitPrice,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'discount' => $this->discount instanceof self ? $this->discount->toArray() : $this->discount,
            'total' => $this->total instanceof self ? $this->total->toArray() : $this->total,
            'totalVAT' => $this->totalVAT instanceof self ? $this->totalVAT->toArray() : $this->totalVAT,
            'taxRateId' => $this->taxRateId instanceof self ? $this->taxRateId->toArray() : $this->taxRateId,
            'isProduct' => $this->isProduct instanceof self ? $this->isProduct->toArray() : $this->isProduct,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('id', $data)) {
            $instance->id = $data['id'];
        }
        if (array_key_exists('productCode', $data)) {
            $instance->productCode = $data['productCode'];
        }
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        if (array_key_exists('unitPrice', $data)) {
            $instance->unitPrice = $data['unitPrice'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('discount', $data)) {
            $instance->discount = $data['discount'];
        }
        if (array_key_exists('total', $data)) {
            $instance->total = $data['total'];
        }
        if (array_key_exists('totalVAT', $data)) {
            $instance->totalVAT = $data['totalVAT'];
        }
        if (array_key_exists('taxRateId', $data)) {
            $instance->taxRateId = $data['taxRateId'];
        }
        if (array_key_exists('isProduct', $data)) {
            $instance->isProduct = $data['isProduct'];
        }
        return $instance;
    }
}
