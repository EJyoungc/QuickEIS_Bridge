<?php

namespace App\DTOs\Mra;

class InitialInventoryResponse
{
    public ?int $totalItems = null;
    public ?int $mappedItems = null;
    public ?int $unmappedItems = null;
    public ?bool $isPartialUpload = null;
    public ?array $skippedItems = null;

    public function toArray(): array
    {
        return [
            'totalItems' => $this->totalItems instanceof self ? $this->totalItems->toArray() : $this->totalItems,
            'mappedItems' => $this->mappedItems instanceof self ? $this->mappedItems->toArray() : $this->mappedItems,
            'unmappedItems' => $this->unmappedItems instanceof self ? $this->unmappedItems->toArray() : $this->unmappedItems,
            'isPartialUpload' => $this->isPartialUpload instanceof self ? $this->isPartialUpload->toArray() : $this->isPartialUpload,
            'skippedItems' => $this->skippedItems instanceof self ? $this->skippedItems->toArray() : $this->skippedItems,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('totalItems', $data)) {
            $instance->totalItems = $data['totalItems'];
        }
        if (array_key_exists('mappedItems', $data)) {
            $instance->mappedItems = $data['mappedItems'];
        }
        if (array_key_exists('unmappedItems', $data)) {
            $instance->unmappedItems = $data['unmappedItems'];
        }
        if (array_key_exists('isPartialUpload', $data)) {
            $instance->isPartialUpload = $data['isPartialUpload'];
        }
        if (array_key_exists('skippedItems', $data)) {
            $instance->skippedItems = $data['skippedItems'];
        }
        return $instance;
    }
}
