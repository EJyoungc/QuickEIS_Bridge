<?php

namespace App\DTOs\Mra;

class TinAuthorizationRequirementResponse
{
    public ?string $tin = null;
    public ?bool $tinExists = null;
    public ?bool $requiresAuthorizationCode = null;

    public function toArray(): array
    {
        return [
            'tin' => $this->tin instanceof self ? $this->tin->toArray() : $this->tin,
            'tinExists' => $this->tinExists instanceof self ? $this->tinExists->toArray() : $this->tinExists,
            'requiresAuthorizationCode' => $this->requiresAuthorizationCode instanceof self ? $this->requiresAuthorizationCode->toArray() : $this->requiresAuthorizationCode,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('tin', $data)) {
            $instance->tin = $data['tin'];
        }
        if (array_key_exists('tinExists', $data)) {
            $instance->tinExists = $data['tinExists'];
        }
        if (array_key_exists('requiresAuthorizationCode', $data)) {
            $instance->requiresAuthorizationCode = $data['requiresAuthorizationCode'];
        }
        return $instance;
    }
}
