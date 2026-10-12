<?php

namespace App\DTOs\Mra;

class InvoiceResponse
{
    public ?string $validationURL = null;
    public ?bool $shouldDownloadLatestConfig = null;
    public ?bool $shouldBlockTerminal = null;
    public ?array $validationErrors = null;

    public function toArray(): array
    {
        return [
            'validationURL' => $this->validationURL instanceof self ? $this->validationURL->toArray() : $this->validationURL,
            'shouldDownloadLatestConfig' => $this->shouldDownloadLatestConfig instanceof self ? $this->shouldDownloadLatestConfig->toArray() : $this->shouldDownloadLatestConfig,
            'shouldBlockTerminal' => $this->shouldBlockTerminal instanceof self ? $this->shouldBlockTerminal->toArray() : $this->shouldBlockTerminal,
            'validationErrors' => $this->validationErrors instanceof self ? $this->validationErrors->toArray() : $this->validationErrors,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('validationURL', $data)) {
            $instance->validationURL = $data['validationURL'];
        }
        if (array_key_exists('shouldDownloadLatestConfig', $data)) {
            $instance->shouldDownloadLatestConfig = $data['shouldDownloadLatestConfig'];
        }
        if (array_key_exists('shouldBlockTerminal', $data)) {
            $instance->shouldBlockTerminal = $data['shouldBlockTerminal'];
        }
        if (array_key_exists('validationErrors', $data)) {
            $instance->validationErrors = $data['validationErrors'];
        }
        return $instance;
    }
}
