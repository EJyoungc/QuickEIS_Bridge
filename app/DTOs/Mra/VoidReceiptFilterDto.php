<?php

namespace App\DTOs\Mra;

class VoidReceiptFilterDto
{
    public ?int $status = null;
    public ?string $invoiceNumber = null;
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?int $page = null;
    public ?int $pageSize = null;

    public function toArray(): array
    {
        return [
            'status' => $this->status instanceof self ? $this->status->toArray() : $this->status,
            'invoiceNumber' => $this->invoiceNumber instanceof self ? $this->invoiceNumber->toArray() : $this->invoiceNumber,
            'startDate' => $this->startDate instanceof self ? $this->startDate->toArray() : $this->startDate,
            'endDate' => $this->endDate instanceof self ? $this->endDate->toArray() : $this->endDate,
            'page' => $this->page instanceof self ? $this->page->toArray() : $this->page,
            'pageSize' => $this->pageSize instanceof self ? $this->pageSize->toArray() : $this->pageSize,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('status', $data)) {
            $instance->status = $data['status'];
        }
        if (array_key_exists('invoiceNumber', $data)) {
            $instance->invoiceNumber = $data['invoiceNumber'];
        }
        if (array_key_exists('startDate', $data)) {
            $instance->startDate = $data['startDate'];
        }
        if (array_key_exists('endDate', $data)) {
            $instance->endDate = $data['endDate'];
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
