<?php

namespace App\DTOs\Mra;

class Vat5CertificateDto
{
    public ?int $id = null;
    public ?string $projectNumber = null;
    public ?string $certificateNumber = null;
    public ?float $quantity = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id instanceof self ? $this->id->toArray() : $this->id,
            'projectNumber' => $this->projectNumber instanceof self ? $this->projectNumber->toArray() : $this->projectNumber,
            'certificateNumber' => $this->certificateNumber instanceof self ? $this->certificateNumber->toArray() : $this->certificateNumber,
            'quantity' => $this->quantity instanceof self ? $this->quantity->toArray() : $this->quantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('id', $data)) {
            $instance->id = $data['id'];
        }
        if (array_key_exists('projectNumber', $data)) {
            $instance->projectNumber = $data['projectNumber'];
        }
        if (array_key_exists('certificateNumber', $data)) {
            $instance->certificateNumber = $data['certificateNumber'];
        }
        if (array_key_exists('quantity', $data)) {
            $instance->quantity = $data['quantity'];
        }
        return $instance;
    }
}
