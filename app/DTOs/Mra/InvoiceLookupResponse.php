<?php

namespace App\DTOs\Mra;

class InvoiceLookupResponse
{
    public ?InvoiceHeader $invoiceHeader = null;
    public ?array $invoiceLineItems = null;
    public ?InvoiceSummary $invoiceSummary = null;
    public ?string $dateSubmitted = null;
    public ?string $validationURL = null;

    public function toArray(): array
    {
        return [
            'invoiceHeader' => $this->invoiceHeader instanceof self ? $this->invoiceHeader->toArray() : $this->invoiceHeader,
            'invoiceLineItems' => $this->invoiceLineItems instanceof self ? $this->invoiceLineItems->toArray() : $this->invoiceLineItems,
            'invoiceSummary' => $this->invoiceSummary instanceof self ? $this->invoiceSummary->toArray() : $this->invoiceSummary,
            'dateSubmitted' => $this->dateSubmitted instanceof self ? $this->dateSubmitted->toArray() : $this->dateSubmitted,
            'validationURL' => $this->validationURL instanceof self ? $this->validationURL->toArray() : $this->validationURL,
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
        if (array_key_exists('dateSubmitted', $data)) {
            $instance->dateSubmitted = $data['dateSubmitted'];
        }
        if (array_key_exists('validationURL', $data)) {
            $instance->validationURL = $data['validationURL'];
        }
        return $instance;
    }
}
