<?php

namespace App\DTOs\Mra;

class TerminalActivationResponse
{
    public ?ActivatedTerminal $activatedTerminal = null;
    public ?Configuration $configuration = null;

    public function toArray(): array
    {
        return [
            'activatedTerminal' => $this->activatedTerminal instanceof self ? $this->activatedTerminal->toArray() : $this->activatedTerminal,
            'configuration' => $this->configuration instanceof self ? $this->configuration->toArray() : $this->configuration,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('activatedTerminal', $data)) {
            $instance->activatedTerminal = $data['activatedTerminal'];
        }
        if (array_key_exists('configuration', $data)) {
            $instance->configuration = $data['configuration'];
        }
        return $instance;
    }
}
