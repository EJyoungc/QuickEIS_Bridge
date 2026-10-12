<?php

namespace App\DTOs\Mra;

class TaxpayerConfiguration
{
    public ?float $versionNo = null;
    public ?string $tin = null;
    public ?bool $isVATRegistered = null;
    public ?string $taxOfficeCode = null;
    public ?TaxOfficeDto $taxOffice = null;
    public ?array $activatedTaxRateIds = null;
    public ?array $activatedTaxrates = null;
    public ?array $activatedLevies = null;

    public function toArray(): array
    {
        return [
            'versionNo' => $this->versionNo instanceof self ? $this->versionNo->toArray() : $this->versionNo,
            'tin' => $this->tin instanceof self ? $this->tin->toArray() : $this->tin,
            'isVATRegistered' => $this->isVATRegistered instanceof self ? $this->isVATRegistered->toArray() : $this->isVATRegistered,
            'taxOfficeCode' => $this->taxOfficeCode instanceof self ? $this->taxOfficeCode->toArray() : $this->taxOfficeCode,
            'taxOffice' => $this->taxOffice instanceof self ? $this->taxOffice->toArray() : $this->taxOffice,
            'activatedTaxRateIds' => $this->activatedTaxRateIds instanceof self ? $this->activatedTaxRateIds->toArray() : $this->activatedTaxRateIds,
            'activatedTaxrates' => $this->activatedTaxrates instanceof self ? $this->activatedTaxrates->toArray() : $this->activatedTaxrates,
            'activatedLevies' => $this->activatedLevies instanceof self ? $this->activatedLevies->toArray() : $this->activatedLevies,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('versionNo', $data)) {
            $instance->versionNo = $data['versionNo'];
        }
        if (array_key_exists('tin', $data)) {
            $instance->tin = $data['tin'];
        }
        if (array_key_exists('isVATRegistered', $data)) {
            $instance->isVATRegistered = $data['isVATRegistered'];
        }
        if (array_key_exists('taxOfficeCode', $data)) {
            $instance->taxOfficeCode = $data['taxOfficeCode'];
        }
        if (array_key_exists('taxOffice', $data)) {
            $instance->taxOffice = $data['taxOffice'];
        }
        if (array_key_exists('activatedTaxRateIds', $data)) {
            $instance->activatedTaxRateIds = $data['activatedTaxRateIds'];
        }
        if (array_key_exists('activatedTaxrates', $data)) {
            $instance->activatedTaxrates = $data['activatedTaxrates'];
        }
        if (array_key_exists('activatedLevies', $data)) {
            $instance->activatedLevies = $data['activatedLevies'];
        }
        return $instance;
    }
}
