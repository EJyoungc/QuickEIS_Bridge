<?php

namespace App\DTOs\Mra;

class InventoryItem
{
    public ?string $barCode = null;
    public ?string $productName = null;
    public ?string $productDescription = null;
    public ?float $unitPrice = null;
    public ?float $quantityInStock = null;
    public ?float $costPrice = null;
    public ?float $sellingPrice = null;
    public ?float $reorderLevel = null;
    public ?float $overQuantityStockLevel = null;

    public function toArray(): array
    {
        return [
            'barCode' => $this->barCode instanceof self ? $this->barCode->toArray() : $this->barCode,
            'productName' => $this->productName instanceof self ? $this->productName->toArray() : $this->productName,
            'productDescription' => $this->productDescription instanceof self ? $this->productDescription->toArray() : $this->productDescription,
            'unitPrice' => $this->unitPrice instanceof self ? $this->unitPrice->toArray() : $this->unitPrice,
            'quantityInStock' => $this->quantityInStock instanceof self ? $this->quantityInStock->toArray() : $this->quantityInStock,
            'costPrice' => $this->costPrice instanceof self ? $this->costPrice->toArray() : $this->costPrice,
            'sellingPrice' => $this->sellingPrice instanceof self ? $this->sellingPrice->toArray() : $this->sellingPrice,
            'reorderLevel' => $this->reorderLevel instanceof self ? $this->reorderLevel->toArray() : $this->reorderLevel,
            'overQuantityStockLevel' => $this->overQuantityStockLevel instanceof self ? $this->overQuantityStockLevel->toArray() : $this->overQuantityStockLevel,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('barCode', $data)) {
            $instance->barCode = $data['barCode'];
        }
        if (array_key_exists('productName', $data)) {
            $instance->productName = $data['productName'];
        }
        if (array_key_exists('productDescription', $data)) {
            $instance->productDescription = $data['productDescription'];
        }
        if (array_key_exists('unitPrice', $data)) {
            $instance->unitPrice = $data['unitPrice'];
        }
        if (array_key_exists('quantityInStock', $data)) {
            $instance->quantityInStock = $data['quantityInStock'];
        }
        if (array_key_exists('costPrice', $data)) {
            $instance->costPrice = $data['costPrice'];
        }
        if (array_key_exists('sellingPrice', $data)) {
            $instance->sellingPrice = $data['sellingPrice'];
        }
        if (array_key_exists('reorderLevel', $data)) {
            $instance->reorderLevel = $data['reorderLevel'];
        }
        if (array_key_exists('overQuantityStockLevel', $data)) {
            $instance->overQuantityStockLevel = $data['overQuantityStockLevel'];
        }
        return $instance;
    }
}
