<?php

namespace App\DTOs\Mra;

class InventoryTransferRequest
{
    public ?bool $fromWarehouseToSite = null;
    public ?bool $siteToWarehouse = null;
    public ?string $fromSiteId = null;
    public ?string $toSiteId = null;
    public ?array $items = null;

    public function toArray(): array
    {
        return [
            'fromWarehouseToSite' => $this->fromWarehouseToSite instanceof self ? $this->fromWarehouseToSite->toArray() : $this->fromWarehouseToSite,
            'siteToWarehouse' => $this->siteToWarehouse instanceof self ? $this->siteToWarehouse->toArray() : $this->siteToWarehouse,
            'fromSiteId' => $this->fromSiteId instanceof self ? $this->fromSiteId->toArray() : $this->fromSiteId,
            'toSiteId' => $this->toSiteId instanceof self ? $this->toSiteId->toArray() : $this->toSiteId,
            'items' => $this->items instanceof self ? $this->items->toArray() : $this->items,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('fromWarehouseToSite', $data)) {
            $instance->fromWarehouseToSite = $data['fromWarehouseToSite'];
        }
        if (array_key_exists('siteToWarehouse', $data)) {
            $instance->siteToWarehouse = $data['siteToWarehouse'];
        }
        if (array_key_exists('fromSiteId', $data)) {
            $instance->fromSiteId = $data['fromSiteId'];
        }
        if (array_key_exists('toSiteId', $data)) {
            $instance->toSiteId = $data['toSiteId'];
        }
        if (array_key_exists('items', $data)) {
            $instance->items = $data['items'];
        }
        return $instance;
    }
}
