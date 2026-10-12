<?php

namespace App\DTOs\Mra;

class UnValidatedAuthorizationCode
{
    public ?string $authorizationCode = null;

    public function toArray(): array
    {
        return [
            'authorizationCode' => $this->authorizationCode instanceof self ? $this->authorizationCode->toArray() : $this->authorizationCode,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('authorizationCode', $data)) {
            $instance->authorizationCode = $data['authorizationCode'];
        }
        return $instance;
    }
}
