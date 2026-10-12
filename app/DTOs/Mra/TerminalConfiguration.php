<?php

namespace App\DTOs\Mra;

class TerminalConfiguration
{
    public ?float $versionNo = null;
    public ?string $terminalLabel = null;
    public ?bool $isActiveTerminal = null;
    public ?string $emailAddress = null;
    public ?string $phoneNumber = null;
    public ?string $tradingName = null;
    public ?array $addressLines = null;
    public ?TerminalSiteDto $terminalSite = null;
    public ?OfflineLimit $offlineLimit = null;

    public function toArray(): array
    {
        return [
            'versionNo' => $this->versionNo instanceof self ? $this->versionNo->toArray() : $this->versionNo,
            'terminalLabel' => $this->terminalLabel instanceof self ? $this->terminalLabel->toArray() : $this->terminalLabel,
            'isActiveTerminal' => $this->isActiveTerminal instanceof self ? $this->isActiveTerminal->toArray() : $this->isActiveTerminal,
            'emailAddress' => $this->emailAddress instanceof self ? $this->emailAddress->toArray() : $this->emailAddress,
            'phoneNumber' => $this->phoneNumber instanceof self ? $this->phoneNumber->toArray() : $this->phoneNumber,
            'tradingName' => $this->tradingName instanceof self ? $this->tradingName->toArray() : $this->tradingName,
            'addressLines' => $this->addressLines instanceof self ? $this->addressLines->toArray() : $this->addressLines,
            'terminalSite' => $this->terminalSite instanceof self ? $this->terminalSite->toArray() : $this->terminalSite,
            'offlineLimit' => $this->offlineLimit instanceof self ? $this->offlineLimit->toArray() : $this->offlineLimit,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('versionNo', $data)) {
            $instance->versionNo = $data['versionNo'];
        }
        if (array_key_exists('terminalLabel', $data)) {
            $instance->terminalLabel = $data['terminalLabel'];
        }
        if (array_key_exists('isActiveTerminal', $data)) {
            $instance->isActiveTerminal = $data['isActiveTerminal'];
        }
        if (array_key_exists('emailAddress', $data)) {
            $instance->emailAddress = $data['emailAddress'];
        }
        if (array_key_exists('phoneNumber', $data)) {
            $instance->phoneNumber = $data['phoneNumber'];
        }
        if (array_key_exists('tradingName', $data)) {
            $instance->tradingName = $data['tradingName'];
        }
        if (array_key_exists('addressLines', $data)) {
            $instance->addressLines = $data['addressLines'];
        }
        if (array_key_exists('terminalSite', $data)) {
            $instance->terminalSite = $data['terminalSite'];
        }
        if (array_key_exists('offlineLimit', $data)) {
            $instance->offlineLimit = $data['offlineLimit'];
        }
        return $instance;
    }
}
