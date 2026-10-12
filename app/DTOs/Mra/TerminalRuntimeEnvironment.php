<?php

namespace App\DTOs\Mra;

class TerminalRuntimeEnvironment
{
    public ?Platform $platform = null;
    public ?POS $pos = null;

    public function toArray(): array
    {
        return [
            'platform' => $this->platform instanceof self ? $this->platform->toArray() : $this->platform,
            'pos' => $this->pos instanceof self ? $this->pos->toArray() : $this->pos,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('platform', $data)) {
            $instance->platform = $data['platform'];
        }
        if (array_key_exists('pos', $data)) {
            $instance->pos = $data['pos'];
        }
        return $instance;
    }
}
