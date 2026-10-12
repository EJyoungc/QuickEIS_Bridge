<?php

namespace App\DTOs\Mra;

class InvoiceSummary
{
    public ?array $taxBreakDown = null;
    public ?array $levyBreakDown = null;
    public ?float $totalVAT = null;
    public ?string $offlineSignature = null;
    public ?float $invoiceTotal = null;
    public ?float $amountTendered = null;

    public function toArray(): array
    {
        return [
            'taxBreakDown' => $this->taxBreakDown instanceof self ? $this->taxBreakDown->toArray() : $this->taxBreakDown,
            'levyBreakDown' => $this->levyBreakDown instanceof self ? $this->levyBreakDown->toArray() : $this->levyBreakDown,
            'totalVAT' => $this->totalVAT instanceof self ? $this->totalVAT->toArray() : $this->totalVAT,
            'offlineSignature' => $this->offlineSignature instanceof self ? $this->offlineSignature->toArray() : $this->offlineSignature,
            'invoiceTotal' => $this->invoiceTotal instanceof self ? $this->invoiceTotal->toArray() : $this->invoiceTotal,
            'amountTendered' => $this->amountTendered instanceof self ? $this->amountTendered->toArray() : $this->amountTendered,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('taxBreakDown', $data)) {
            $instance->taxBreakDown = $data['taxBreakDown'];
        }
        if (array_key_exists('levyBreakDown', $data)) {
            $instance->levyBreakDown = $data['levyBreakDown'];
        }
        if (array_key_exists('totalVAT', $data)) {
            $instance->totalVAT = $data['totalVAT'];
        }
        if (array_key_exists('offlineSignature', $data)) {
            $instance->offlineSignature = $data['offlineSignature'];
        }
        if (array_key_exists('invoiceTotal', $data)) {
            $instance->invoiceTotal = $data['invoiceTotal'];
        }
        if (array_key_exists('amountTendered', $data)) {
            $instance->amountTendered = $data['amountTendered'];
        }
        return $instance;
    }
}
