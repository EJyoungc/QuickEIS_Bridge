<?php

namespace App\DTOs\Mra;

class PongResponse
{
    public ?string $serverDate = null;

    public function toArray(): array
    {
        return [
            'serverDate' => $this->serverDate instanceof self ? $this->serverDate->toArray() : $this->serverDate,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('serverDate', $data)) {
            $instance->serverDate = $data['serverDate'];
        }
        return $instance;
    }
}
