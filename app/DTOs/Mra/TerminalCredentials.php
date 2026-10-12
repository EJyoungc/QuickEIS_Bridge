<?php

namespace App\DTOs\Mra;

class TerminalCredentials
{
    public ?string $jwtToken = null;
    public ?string $secretKey = null;

    public function toArray(): array
    {
        return [
            'jwtToken' => $this->jwtToken instanceof self ? $this->jwtToken->toArray() : $this->jwtToken,
            'secretKey' => $this->secretKey instanceof self ? $this->secretKey->toArray() : $this->secretKey,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('jwtToken', $data)) {
            $instance->jwtToken = $data['jwtToken'];
        }
        if (array_key_exists('secretKey', $data)) {
            $instance->secretKey = $data['secretKey'];
        }
        return $instance;
    }
}
