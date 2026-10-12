<?php

namespace App\DTOs\Mra;

class TerminalUnblockStatusResponse
{
    public ?bool $isUnblocked = null;

    public function toArray(): array
    {
        return [
            'isUnblocked' => $this->isUnblocked instanceof self ? $this->isUnblocked->toArray() : $this->isUnblocked,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('isUnblocked', $data)) {
            $instance->isUnblocked = $data['isUnblocked'];
        }
        return $instance;
    }
}
