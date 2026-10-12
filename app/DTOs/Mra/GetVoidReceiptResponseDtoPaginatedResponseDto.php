<?php

namespace App\DTOs\Mra;

class GetVoidReceiptResponseDtoPaginatedResponseDto
{
    public ?array $items = null;
    public ?int $page = null;
    public ?int $pageSize = null;
    public ?int $totalCount = null;

    public function toArray(): array
    {
        return [
            'items' => $this->items instanceof self ? $this->items->toArray() : $this->items,
            'page' => $this->page instanceof self ? $this->page->toArray() : $this->page,
            'pageSize' => $this->pageSize instanceof self ? $this->pageSize->toArray() : $this->pageSize,
            'totalCount' => $this->totalCount instanceof self ? $this->totalCount->toArray() : $this->totalCount,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('items', $data)) {
            $instance->items = $data['items'];
        }
        if (array_key_exists('page', $data)) {
            $instance->page = $data['page'];
        }
        if (array_key_exists('pageSize', $data)) {
            $instance->pageSize = $data['pageSize'];
        }
        if (array_key_exists('totalCount', $data)) {
            $instance->totalCount = $data['totalCount'];
        }
        return $instance;
    }
}
