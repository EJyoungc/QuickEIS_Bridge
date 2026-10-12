<?php

namespace App\DTOs\Mra;

class SupplierDto
{
    public ?int $supplierId = null;
    public ?string $supplierName = null;
    public ?string $supplierContactEmail = null;
    public ?string $supplierContactPhone = null;
    public ?string $supplierTin = null;

    public function toArray(): array
    {
        return [
            'supplierId' => $this->supplierId instanceof self ? $this->supplierId->toArray() : $this->supplierId,
            'supplierName' => $this->supplierName instanceof self ? $this->supplierName->toArray() : $this->supplierName,
            'supplierContactEmail' => $this->supplierContactEmail instanceof self ? $this->supplierContactEmail->toArray() : $this->supplierContactEmail,
            'supplierContactPhone' => $this->supplierContactPhone instanceof self ? $this->supplierContactPhone->toArray() : $this->supplierContactPhone,
            'supplierTin' => $this->supplierTin instanceof self ? $this->supplierTin->toArray() : $this->supplierTin,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('supplierId', $data)) {
            $instance->supplierId = $data['supplierId'];
        }
        if (array_key_exists('supplierName', $data)) {
            $instance->supplierName = $data['supplierName'];
        }
        if (array_key_exists('supplierContactEmail', $data)) {
            $instance->supplierContactEmail = $data['supplierContactEmail'];
        }
        if (array_key_exists('supplierContactPhone', $data)) {
            $instance->supplierContactPhone = $data['supplierContactPhone'];
        }
        if (array_key_exists('supplierTin', $data)) {
            $instance->supplierTin = $data['supplierTin'];
        }
        return $instance;
    }
}
