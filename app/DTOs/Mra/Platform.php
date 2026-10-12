<?php

namespace App\DTOs\Mra;

class Platform
{
    public ?string $osName = null;
    public ?string $osVersion = null;
    public ?string $osBuild = null;
    public ?string $macAddress = null;

    public function toArray(): array
    {
        return [
            'osName' => $this->osName instanceof self ? $this->osName->toArray() : $this->osName,
            'osVersion' => $this->osVersion instanceof self ? $this->osVersion->toArray() : $this->osVersion,
            'osBuild' => $this->osBuild instanceof self ? $this->osBuild->toArray() : $this->osBuild,
            'macAddress' => $this->macAddress instanceof self ? $this->macAddress->toArray() : $this->macAddress,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('osName', $data)) {
            $instance->osName = $data['osName'];
        }
        if (array_key_exists('osVersion', $data)) {
            $instance->osVersion = $data['osVersion'];
        }
        if (array_key_exists('osBuild', $data)) {
            $instance->osBuild = $data['osBuild'];
        }
        if (array_key_exists('macAddress', $data)) {
            $instance->macAddress = $data['macAddress'];
        }
        return $instance;
    }
}
