<?php

namespace App\DTOs\Mra;

class RawmaterialConversion
{
    public ?string $productionBatchId = null;
    public ?string $productionDate = null;
    public ?array $rawMaterials = null;
    public ?array $finishedProducts = null;

    public function toArray(): array
    {
        return [
            'productionBatchId' => $this->productionBatchId instanceof self ? $this->productionBatchId->toArray() : $this->productionBatchId,
            'productionDate' => $this->productionDate instanceof self ? $this->productionDate->toArray() : $this->productionDate,
            'rawMaterials' => $this->rawMaterials instanceof self ? $this->rawMaterials->toArray() : $this->rawMaterials,
            'finishedProducts' => $this->finishedProducts instanceof self ? $this->finishedProducts->toArray() : $this->finishedProducts,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('productionBatchId', $data)) {
            $instance->productionBatchId = $data['productionBatchId'];
        }
        if (array_key_exists('productionDate', $data)) {
            $instance->productionDate = $data['productionDate'];
        }
        if (array_key_exists('rawMaterials', $data)) {
            $instance->rawMaterials = $data['rawMaterials'];
        }
        if (array_key_exists('finishedProducts', $data)) {
            $instance->finishedProducts = $data['finishedProducts'];
        }
        return $instance;
    }
}
