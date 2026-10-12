<?php

namespace App\DTOs\Mra;

class TaxpayerRawMaterialDtoGenericInventoryDtoResponse
{
    public ?array $stocks = null;
    public ?int $total = null;
    public ?int $page = null;
    public ?int $pageSize = null;

    public function toArray(): array
    {
        return [
            'stocks' => $this->stocks instanceof self ? $this->stocks->toArray() : $this->stocks,
            'total' => $this->total instanceof self ? $this->total->toArray() : $this->total,
            'page' => $this->page instanceof self ? $this->page->toArray() : $this->page,
            'pageSize' => $this->pageSize instanceof self ? $this->pageSize->toArray() : $this->pageSize,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('stocks', $data)) {
            $instance->stocks = $data['stocks'];
        }
        if (array_key_exists('total', $data)) {
            $instance->total = $data['total'];
        }
        if (array_key_exists('page', $data)) {
            $instance->page = $data['page'];
        }
        if (array_key_exists('pageSize', $data)) {
            $instance->pageSize = $data['pageSize'];
        }
        return $instance;
    }
}
