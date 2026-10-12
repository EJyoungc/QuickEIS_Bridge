<?php

namespace App\DTOs\Mra;

class ValidatedAuthorizationCode
{
    public ?bool $isValidAuthorizationCode = null;
    public ?string $authorizationCode = null;
    public ?string $authorizationReason = null;
    public ?string $generatedBy = null;
    public ?string $generatedOn = null;
    public ?string $expiresOn = null;
    public ?string $usedOn = null;

    public function toArray(): array
    {
        return [
            'isValidAuthorizationCode' => $this->isValidAuthorizationCode instanceof self ? $this->isValidAuthorizationCode->toArray() : $this->isValidAuthorizationCode,
            'authorizationCode' => $this->authorizationCode instanceof self ? $this->authorizationCode->toArray() : $this->authorizationCode,
            'authorizationReason' => $this->authorizationReason instanceof self ? $this->authorizationReason->toArray() : $this->authorizationReason,
            'generatedBy' => $this->generatedBy instanceof self ? $this->generatedBy->toArray() : $this->generatedBy,
            'generatedOn' => $this->generatedOn instanceof self ? $this->generatedOn->toArray() : $this->generatedOn,
            'expiresOn' => $this->expiresOn instanceof self ? $this->expiresOn->toArray() : $this->expiresOn,
            'usedOn' => $this->usedOn instanceof self ? $this->usedOn->toArray() : $this->usedOn,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('isValidAuthorizationCode', $data)) {
            $instance->isValidAuthorizationCode = $data['isValidAuthorizationCode'];
        }
        if (array_key_exists('authorizationCode', $data)) {
            $instance->authorizationCode = $data['authorizationCode'];
        }
        if (array_key_exists('authorizationReason', $data)) {
            $instance->authorizationReason = $data['authorizationReason'];
        }
        if (array_key_exists('generatedBy', $data)) {
            $instance->generatedBy = $data['generatedBy'];
        }
        if (array_key_exists('generatedOn', $data)) {
            $instance->generatedOn = $data['generatedOn'];
        }
        if (array_key_exists('expiresOn', $data)) {
            $instance->expiresOn = $data['expiresOn'];
        }
        if (array_key_exists('usedOn', $data)) {
            $instance->usedOn = $data['usedOn'];
        }
        return $instance;
    }
}
