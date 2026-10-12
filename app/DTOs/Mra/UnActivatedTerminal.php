<?php

namespace App\DTOs\Mra;

class UnActivatedTerminal
{
    public ?string $terminalActivationCode = null;
    public ?TerminalRuntimeEnvironment $environment = null;

    public function toArray(): array
    {
        return [
            'terminalActivationCode' => $this->terminalActivationCode instanceof self ? $this->terminalActivationCode->toArray() : $this->terminalActivationCode,
            'environment' => $this->environment instanceof self ? $this->environment->toArray() : $this->environment,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('terminalActivationCode', $data)) {
            $instance->terminalActivationCode = $data['terminalActivationCode'];
        }
        if (array_key_exists('environment', $data)) {
            $instance->environment = $data['environment'];
        }
        return $instance;
    }
}
