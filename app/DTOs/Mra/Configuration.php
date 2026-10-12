<?php

namespace App\DTOs\Mra;

class Configuration
{
    public ?TaxConfiguration $globalConfiguration = null;
    public ?TerminalConfiguration $terminalConfiguration = null;
    public ?TaxpayerConfiguration $taxpayerConfiguration = null;

    public function toArray(): array
    {
        return [
            'globalConfiguration' => $this->globalConfiguration instanceof self ? $this->globalConfiguration->toArray() : $this->globalConfiguration,
            'terminalConfiguration' => $this->terminalConfiguration instanceof self ? $this->terminalConfiguration->toArray() : $this->terminalConfiguration,
            'taxpayerConfiguration' => $this->taxpayerConfiguration instanceof self ? $this->taxpayerConfiguration->toArray() : $this->taxpayerConfiguration,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('globalConfiguration', $data)) {
            $instance->globalConfiguration = $data['globalConfiguration'];
        }
        if (array_key_exists('terminalConfiguration', $data)) {
            $instance->terminalConfiguration = $data['terminalConfiguration'];
        }
        if (array_key_exists('taxpayerConfiguration', $data)) {
            $instance->taxpayerConfiguration = $data['taxpayerConfiguration'];
        }
        return $instance;
    }
}
