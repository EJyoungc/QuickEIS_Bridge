<?php

namespace App\DTOs\Mra;

class OfflineLimit
{
    public ?float $maxTransactionAgeInHours = null;
    public ?float $maxCummulativeAmount = null;

    public function toArray(): array
    {
        return [
            'maxTransactionAgeInHours' => $this->maxTransactionAgeInHours instanceof self ? $this->maxTransactionAgeInHours->toArray() : $this->maxTransactionAgeInHours,
            'maxCummulativeAmount' => $this->maxCummulativeAmount instanceof self ? $this->maxCummulativeAmount->toArray() : $this->maxCummulativeAmount,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('maxTransactionAgeInHours', $data)) {
            $instance->maxTransactionAgeInHours = $data['maxTransactionAgeInHours'];
        }
        if (array_key_exists('maxCummulativeAmount', $data)) {
            $instance->maxCummulativeAmount = $data['maxCummulativeAmount'];
        }
        return $instance;
    }
}
