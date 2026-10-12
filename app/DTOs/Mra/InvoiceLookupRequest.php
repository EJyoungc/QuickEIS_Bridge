<?php

namespace App\DTOs\Mra;

class InvoiceLookupRequest
{
    public ?string $invoiceNumber = null;

    public function toArray(): array
    {
        return [
            'invoiceNumber' => $this->invoiceNumber instanceof self ? $this->invoiceNumber->toArray() : $this->invoiceNumber,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('invoiceNumber', $data)) {
            $instance->invoiceNumber = $data['invoiceNumber'];
        }
        return $instance;
    }
}
