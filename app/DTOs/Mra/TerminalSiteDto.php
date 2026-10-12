<?php

namespace App\DTOs\Mra;

class TerminalSiteDto
{
    public ?string $siteId = null;
    public ?string $siteName = null;

    public function toArray(): array
    {
        return [
            'siteId' => $this->siteId instanceof self ? $this->siteId->toArray() : $this->siteId,
            'siteName' => $this->siteName instanceof self ? $this->siteName->toArray() : $this->siteName,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('siteId', $data)) {
            $instance->siteId = $data['siteId'];
        }
        if (array_key_exists('siteName', $data)) {
            $instance->siteName = $data['siteName'];
        }
        return $instance;
    }
}
