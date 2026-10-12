<?php

namespace App\DTOs\Mra;

class GoodsReceivingItemModel
{
    public ?string $itemCode = null;
    public ?string $description = null;
    public ?float $quantityOrdered = null;
    public ?float $quantityReceived = null;
    public ?string $unitOfMeasure = null;
    public ?float $unitPrice = null;
    public ?float $totalPrice = null;
    public ?bool $isFinishedProduct = null;

    public function toArray(): array
    {
        return [
            'itemCode' => $this->itemCode instanceof self ? $this->itemCode->toArray() : $this->itemCode,
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
            'quantityOrdered' => $this->quantityOrdered instanceof self ? $this->quantityOrdered->toArray() : $this->quantityOrdered,
            'quantityReceived' => $this->quantityReceived instanceof self ? $this->quantityReceived->toArray() : $this->quantityReceived,
            'unitOfMeasure' => $this->unitOfMeasure instanceof self ? $this->unitOfMeasure->toArray() : $this->unitOfMeasure,
            'unitPrice' => $this->unitPrice instanceof self ? $this->unitPrice->toArray() : $this->unitPrice,
            'totalPrice' => $this->totalPrice instanceof self ? $this->totalPrice->toArray() : $this->totalPrice,
            'isFinishedProduct' => $this->isFinishedProduct instanceof self ? $this->isFinishedProduct->toArray() : $this->isFinishedProduct,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('itemCode', $data)) {
            $instance->itemCode = $data['itemCode'];
        }
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        if (array_key_exists('quantityOrdered', $data)) {
            $instance->quantityOrdered = $data['quantityOrdered'];
        }
        if (array_key_exists('quantityReceived', $data)) {
            $instance->quantityReceived = $data['quantityReceived'];
        }
        if (array_key_exists('unitOfMeasure', $data)) {
            $instance->unitOfMeasure = $data['unitOfMeasure'];
        }
        if (array_key_exists('unitPrice', $data)) {
            $instance->unitPrice = $data['unitPrice'];
        }
        if (array_key_exists('totalPrice', $data)) {
            $instance->totalPrice = $data['totalPrice'];
        }
        if (array_key_exists('isFinishedProduct', $data)) {
            $instance->isFinishedProduct = $data['isFinishedProduct'];
        }
        return $instance;
    }
}
