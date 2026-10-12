<?php

namespace App\DTOs\Mra;

class VoidReceiptCreateDto
{
    public ?string $receiptNumber = null;
    public ?string $reason = null;
    public ?string $supportingDocuments = null;

    public function toArray(): array
    {
        return [
            'receiptNumber' => $this->receiptNumber instanceof self ? $this->receiptNumber->toArray() : $this->receiptNumber,
            'reason' => $this->reason instanceof self ? $this->reason->toArray() : $this->reason,
            'supportingDocuments' => $this->supportingDocuments instanceof self ? $this->supportingDocuments->toArray() : $this->supportingDocuments,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('receiptNumber', $data)) {
            $instance->receiptNumber = $data['receiptNumber'];
        }
        if (array_key_exists('reason', $data)) {
            $instance->reason = $data['reason'];
        }
        if (array_key_exists('supportingDocuments', $data)) {
            $instance->supportingDocuments = $data['supportingDocuments'];
        }
        return $instance;
    }
}
