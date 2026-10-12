<?php

namespace App\DTOs\Mra;

class VoidReceiptResponseDto
{
    public ?string $invoiceNumber = null;
    public ?string $requestReason = null;
    public ?string $issueDate = null;
    public ?string $requestedBy = null;
    public ?string $requestedOn = null;
    public ?string $approvalStatus = null;

    public function toArray(): array
    {
        return [
            'invoiceNumber' => $this->invoiceNumber instanceof self ? $this->invoiceNumber->toArray() : $this->invoiceNumber,
            'requestReason' => $this->requestReason instanceof self ? $this->requestReason->toArray() : $this->requestReason,
            'issueDate' => $this->issueDate instanceof self ? $this->issueDate->toArray() : $this->issueDate,
            'requestedBy' => $this->requestedBy instanceof self ? $this->requestedBy->toArray() : $this->requestedBy,
            'requestedOn' => $this->requestedOn instanceof self ? $this->requestedOn->toArray() : $this->requestedOn,
            'approvalStatus' => $this->approvalStatus instanceof self ? $this->approvalStatus->toArray() : $this->approvalStatus,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('invoiceNumber', $data)) {
            $instance->invoiceNumber = $data['invoiceNumber'];
        }
        if (array_key_exists('requestReason', $data)) {
            $instance->requestReason = $data['requestReason'];
        }
        if (array_key_exists('issueDate', $data)) {
            $instance->issueDate = $data['issueDate'];
        }
        if (array_key_exists('requestedBy', $data)) {
            $instance->requestedBy = $data['requestedBy'];
        }
        if (array_key_exists('requestedOn', $data)) {
            $instance->requestedOn = $data['requestedOn'];
        }
        if (array_key_exists('approvalStatus', $data)) {
            $instance->approvalStatus = $data['approvalStatus'];
        }
        return $instance;
    }
}
