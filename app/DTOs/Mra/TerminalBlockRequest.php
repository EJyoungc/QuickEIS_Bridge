<?php

namespace App\DTOs\Mra;

class TerminalBlockRequest
{
    public ?string $terminalId = null;

    public function toArray(): array
    {
        return [
            'terminalId' => $this->terminalId instanceof self ? $this->terminalId->toArray() : $this->terminalId,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('terminalId', $data)) {
            $instance->terminalId = $data['terminalId'];
        }
        return $instance;
    }
}
