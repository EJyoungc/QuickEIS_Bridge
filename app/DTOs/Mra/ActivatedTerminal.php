<?php

namespace App\DTOs\Mra;

class ActivatedTerminal
{
    public ?string $terminalId = null;
    public ?int $terminalPosition = null;
    public ?int $taxpayerId = null;
    public ?string $activationDate = null;
    public ?TerminalCredentials $terminalCredentials = null;

    public function toArray(): array
    {
        return [
            'terminalId' => $this->terminalId instanceof self ? $this->terminalId->toArray() : $this->terminalId,
            'terminalPosition' => $this->terminalPosition instanceof self ? $this->terminalPosition->toArray() : $this->terminalPosition,
            'taxpayerId' => $this->taxpayerId instanceof self ? $this->taxpayerId->toArray() : $this->taxpayerId,
            'activationDate' => $this->activationDate instanceof self ? $this->activationDate->toArray() : $this->activationDate,
            'terminalCredentials' => $this->terminalCredentials instanceof self ? $this->terminalCredentials->toArray() : $this->terminalCredentials,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('terminalId', $data)) {
            $instance->terminalId = $data['terminalId'];
        }
        if (array_key_exists('terminalPosition', $data)) {
            $instance->terminalPosition = $data['terminalPosition'];
        }
        if (array_key_exists('taxpayerId', $data)) {
            $instance->taxpayerId = $data['taxpayerId'];
        }
        if (array_key_exists('activationDate', $data)) {
            $instance->activationDate = $data['activationDate'];
        }
        if (array_key_exists('terminalCredentials', $data)) {
            $instance->terminalCredentials = $data['terminalCredentials'];
        }
        return $instance;
    }
}
