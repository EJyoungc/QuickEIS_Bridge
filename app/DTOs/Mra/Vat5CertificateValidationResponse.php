<?php

namespace App\DTOs\Mra;

class Vat5CertificateValidationResponse
{
    public ?string $projectNumber = null;
    public ?string $vat5CertificateNumber = null;
    public ?float $quantity = null;
    public ?string $dateOfIssue = null;
    public ?string $dateOfExpiry = null;
    public ?bool $isValid = null;

    public function toArray(): array
    {
        return [
            'projectNumber' => $this->projectNumber instanceof self ? $this->projectNumber->toArray() : $this->projectNumber,
            'vat5CertificateNumber' => $this->vat5CertificateNumber instanceof self ? $this->vat5CertificateNumber->toArray() : $this->vat5CertificateNumber,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
            'dateOfIssue' => $this->dateOfIssue instanceof self ? $this->dateOfIssue->toArray() : $this->dateOfIssue,
            'dateOfExpiry' => $this->dateOfExpiry instanceof self ? $this->dateOfExpiry->toArray() : $this->dateOfExpiry,
            'isValid' => $this->isValid instanceof self ? $this->isValid->toArray() : $this->isValid,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('projectNumber', $data)) {
            $instance->projectNumber = $data['projectNumber'];
        }
        if (array_key_exists('vat5CertificateNumber', $data)) {
            $instance->vat5CertificateNumber = $data['vat5CertificateNumber'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        if (array_key_exists('dateOfIssue', $data)) {
            $instance->dateOfIssue = $data['dateOfIssue'];
        }
        if (array_key_exists('dateOfExpiry', $data)) {
            $instance->dateOfExpiry = $data['dateOfExpiry'];
        }
        if (array_key_exists('isValid', $data)) {
            $instance->isValid = $data['isValid'];
        }
        return $instance;
    }
}
