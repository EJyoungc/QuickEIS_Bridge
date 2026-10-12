<?php

namespace App\DTOs\Mra;

class GoodsReceivingModel
{
    public ?int $supplierId = null;
    public ?string $deliveryNoteNumber = null;
    public ?string $receivingDate = null;
    public ?string $purchaseOrderNumber = null;
    public ?string $receivedBy = null;
    public ?int $totalItems = null;
    public ?float $totalQuantity = null;
    public ?float $totalValue = null;
    public ?string $notes = null;
    public ?array $items = null;

    public function toArray(): array
    {
        return [
            'supplierId' => $this->supplierId instanceof self ? $this->supplierId->toArray() : $this->supplierId,
            'deliveryNoteNumber' => $this->deliveryNoteNumber instanceof self ? $this->deliveryNoteNumber->toArray() : $this->deliveryNoteNumber,
            'receivingDate' => $this->receivingDate instanceof self ? $this->receivingDate->toArray() : $this->receivingDate,
            'purchaseOrderNumber' => $this->purchaseOrderNumber instanceof self ? $this->purchaseOrderNumber->toArray() : $this->purchaseOrderNumber,
            'receivedBy' => $this->receivedBy instanceof self ? $this->receivedBy->toArray() : $this->receivedBy,
            'totalItems' => $this->totalItems instanceof self ? $this->totalItems->toArray() : $this->totalItems,
            'totalQuantity' => $this->totalQuantity instanceof self ? $this->totalQuantity->toArray() : $this->totalQuantity,
            'totalValue' => $this->totalValue instanceof self ? $this->totalValue->toArray() : $this->totalValue,
            'notes' => $this->notes instanceof self ? $this->notes->toArray() : $this->notes,
            'items' => $this->items instanceof self ? $this->items->toArray() : $this->items,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('supplierId', $data)) {
            $instance->supplierId = $data['supplierId'];
        }
        if (array_key_exists('deliveryNoteNumber', $data)) {
            $instance->deliveryNoteNumber = $data['deliveryNoteNumber'];
        }
        if (array_key_exists('receivingDate', $data)) {
            $instance->receivingDate = $data['receivingDate'];
        }
        if (array_key_exists('purchaseOrderNumber', $data)) {
            $instance->purchaseOrderNumber = $data['purchaseOrderNumber'];
        }
        if (array_key_exists('receivedBy', $data)) {
            $instance->receivedBy = $data['receivedBy'];
        }
        if (array_key_exists('totalItems', $data)) {
            $instance->totalItems = $data['totalItems'];
        }
        if (array_key_exists('totalQuantity', $data)) {
            $instance->totalQuantity = $data['totalQuantity'];
        }
        if (array_key_exists('totalValue', $data)) {
            $instance->totalValue = $data['totalValue'];
        }
        if (array_key_exists('notes', $data)) {
            $instance->notes = $data['notes'];
        }
        if (array_key_exists('items', $data)) {
            $instance->items = $data['items'];
        }
        return $instance;
    }
}
