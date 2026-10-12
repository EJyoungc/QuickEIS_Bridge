<?php

namespace App\DTOs\Mra;

class ProductsInventoryResponse
{
    public ?string $productCode = null;
    public ?string $productName = null;
    public ?string $description = null;
    public ?float $quantity = null;
    public ?string $unitOfMeasure = null;
    public ?float $price = null;
    public ?string $siteId = null;
    public ?string $productExpiryDate = null;
    public ?float $minimumStockLevel = null;
    public ?string $taxRateId = null;
    public ?bool $isProduct = null;

    public function toArray(): array
    {
        return [
            'productCode' => $this->productCode instanceof self ? $this->productCode->toArray() : $this->productCode,
            'productName' => $this->productName instanceof self ? $this->productName->toArray() : $this->productName,
            'description' => $this->description instanceof self ? $this->description->toArray() : $this->description,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'unitOfMeasure' => $this->unitOfMeasure instanceof self ? $this->unitOfMeasure->toArray() : $this->unitOfMeasure,
            'price' => $this->price instanceof self ? $this->price->toArray() : $this->price,
            'siteId' => $this->siteId instanceof self ? $this->siteId->toArray() : $this->siteId,
            'productExpiryDate' => $this->productExpiryDate instanceof self ? $this->productExpiryDate->toArray() : $this->productExpiryDate,
            'minimumStockLevel' => $this->minimumStockLevel instanceof self ? $this->minimumStockLevel->toArray() : $this->minimumStockLevel,
            'taxRateId' => $this->taxRateId instanceof self ? $this->taxRateId->toArray() : $this->taxRateId,
            'isProduct' => $this->isProduct instanceof self ? $this->isProduct->toArray() : $this->isProduct,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productCode', $data)) {
            $instance->productCode = $data['productCode'];
        }
        if (array_key_exists('productName', $data)) {
            $instance->productName = $data['productName'];
        }
        if (array_key_exists('description', $data)) {
            $instance->description = $data['description'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('unitOfMeasure', $data)) {
            $instance->unitOfMeasure = $data['unitOfMeasure'];
        }
        if (array_key_exists('price', $data)) {
            $instance->price = $data['price'];
        }
        if (array_key_exists('siteId', $data)) {
            $instance->siteId = $data['siteId'];
        }
        if (array_key_exists('productExpiryDate', $data)) {
            $instance->productExpiryDate = $data['productExpiryDate'];
        }
        if (array_key_exists('minimumStockLevel', $data)) {
            $instance->minimumStockLevel = $data['minimumStockLevel'];
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
