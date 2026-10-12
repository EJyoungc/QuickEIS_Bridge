<?php

namespace App\DTOs\Mra;

class TerminalBlockResponse
{
    public ?bool $isBlocked = null;
    public ?string $blockingReason = null;
    public ?string $blockedAt = null;

    public function toArray(): array
    {
        return [
            'isBlocked' => $this->isBlocked instanceof self ? $this->isBlocked->toArray() : $this->isBlocked,
            'blockingReason' => $this->blockingReason instanceof self ? $this->blockingReason->toArray() : $this->blockingReason,
            'blockedAt' => $this->blockedAt instanceof self ? $this->blockedAt->toArray() : $this->blockedAt,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('isBlocked', $data)) {
            $instance->isBlocked = $data['isBlocked'];
        }
        if (array_key_exists('blockingReason', $data)) {
            $instance->blockingReason = $data['blockingReason'];
        }
        if (array_key_exists('blockedAt', $data)) {
            $instance->blockedAt = $data['blockedAt'];
        }
        return $instance;
    }
}
