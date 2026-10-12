<?php

namespace App\DTOs\Mra;

class TaxpayerRawMaterialDto
{
    public ?string $rawMaterialName = null;
    public ?string $rawMaterialDescription = null;
    public ?string $barcode = null;
    public ?float $currentQuantity = null;
    public ?string $uom = null;

    public function toArray(): array
    {
        return [
            'rawMaterialName' => $this->rawMaterialName instanceof self ? $this->rawMaterialName->toArray() : $this->rawMaterialName,
            'rawMaterialDescription' => $this->rawMaterialDescription instanceof self ? $this->rawMaterialDescription->toArray() : $this->rawMaterialDescription,
            'barcode' => $this->barcode instanceof self ? $this->barcode->toArray() : $this->barcode,
            'currentQuantity' => $this->currentQuantity instanceof self ? $this->currentQuantity->toArray() : $this->currentQuantity,
            'uom' => $this->uom instanceof self ? $this->uom->toArray() : $this->uom,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('rawMaterialName', $data)) {
            $instance->rawMaterialName = $data['rawMaterialName'];
        }
        if (array_key_exists('rawMaterialDescription', $data)) {
            $instance->rawMaterialDescription = $data['rawMaterialDescription'];
        }
        if (array_key_exists('barcode', $data)) {
            $instance->barcode = $data['barcode'];
        }
        if (array_key_exists('currentQuantity', $data)) {
            $instance->currentQuantity = $data['currentQuantity'];
        }
        if (array_key_exists('uom', $data)) {
            $instance->uom = $data['uom'];
        }
        return $instance;
    }
}
