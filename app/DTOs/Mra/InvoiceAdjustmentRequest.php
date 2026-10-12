<?php

namespace App\DTOs\Mra;

class InvoiceAdjustmentRequest
{
    public ?InvoiceHeader $invoiceHeader = null;
    public ?array $invoiceLineItems = null;
    public ?InvoiceSummary $invoiceSummary = null;
    public ?string $reasonForAdjustment = null;

    public function toArray(): array
    {
        return [
            'invoiceHeader' => $this->invoiceHeader instanceof self ? $this->invoiceHeader->toArray() : $this->invoiceHeader,
            'invoiceLineItems' => $this->invoiceLineItems instanceof self ? $this->invoiceLineItems->toArray() : $this->invoiceLineItems,
            'invoiceSummary' => $this->invoiceSummary instanceof self ? $this->invoiceSummary->toArray() : $this->invoiceSummary,
            'reasonForAdjustment' => $this->reasonForAdjustment instanceof self ? $this->reasonForAdjustment->toArray() : $this->reasonForAdjustment,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('invoiceHeader', $data)) {
            $instance->invoiceHeader = $data['invoiceHeader'];
        }
        if (array_key_exists('invoiceLineItems', $data)) {
            $instance->invoiceLineItems = $data['invoiceLineItems'];
        }
        if (array_key_exists('invoiceSummary', $data)) {
            $instance->invoiceSummary = $data['invoiceSummary'];
        }
        if (array_key_exists('reasonForAdjustment', $data)) {
            $instance->reasonForAdjustment = $data['reasonForAdjustment'];
        }
        return $instance;
    }
}
