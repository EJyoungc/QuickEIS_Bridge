<?php

namespace App\DTOs\Mra;

class InvoiceAdjustmentResponse
{
    public ?string $invoiceNumber = null;
    public ?string $originalInvoiceNumber = null;
    public ?string $noteType = null;
    public ?string $validationUrl = null;
    public ?float $invoiceTotal = null;
    public ?float $totalVat = null;
    public ?array $lineItems = null;
    public ?array $serviceLineItems = null;

    public function toArray(): array
    {
        return [
            'invoiceNumber' => $this->invoiceNumber instanceof self ? $this->invoiceNumber->toArray() : $this->invoiceNumber,
            'originalInvoiceNumber' => $this->originalInvoiceNumber instanceof self ? $this->originalInvoiceNumber->toArray() : $this->originalInvoiceNumber,
            'noteType' => $this->noteType instanceof self ? $this->noteType->toArray() : $this->noteType,
            'validationUrl' => $this->validationUrl instanceof self ? $this->validationUrl->toArray() : $this->validationUrl,
            'invoiceTotal' => $this->invoiceTotal instanceof self ? $this->invoiceTotal->toArray() : $this->invoiceTotal,
            'totalVat' => $this->totalVat instanceof self ? $this->totalVat->toArray() : $this->totalVat,
            'lineItems' => $this->lineItems instanceof self ? $this->lineItems->toArray() : $this->lineItems,
            'serviceLineItems' => $this->serviceLineItems instanceof self ? $this->serviceLineItems->toArray() : $this->serviceLineItems,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('invoiceNumber', $data)) {
            $instance->invoiceNumber = $data['invoiceNumber'];
        }
        if (array_key_exists('originalInvoiceNumber', $data)) {
            $instance->originalInvoiceNumber = $data['originalInvoiceNumber'];
        }
        if (array_key_exists('noteType', $data)) {
            $instance->noteType = $data['noteType'];
        }
        if (array_key_exists('validationUrl', $data)) {
            $instance->validationUrl = $data['validationUrl'];
        }
        if (array_key_exists('invoiceTotal', $data)) {
            $instance->invoiceTotal = $data['invoiceTotal'];
        }
        if (array_key_exists('totalVat', $data)) {
            $instance->totalVat = $data['totalVat'];
        }
        if (array_key_exists('lineItems', $data)) {
            $instance->lineItems = $data['lineItems'];
        }
        if (array_key_exists('serviceLineItems', $data)) {
            $instance->serviceLineItems = $data['serviceLineItems'];
        }
        return $instance;
    }
}
