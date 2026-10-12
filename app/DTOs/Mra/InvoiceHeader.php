<?php

namespace App\DTOs\Mra;

class InvoiceHeader
{
    public ?string $invoiceNumber = null;
    public ?string $invoiceDateTime = null;
    public ?string $sellerTIN = null;
    public ?string $buyerTIN = null;
    public ?string $buyerName = null;
    public ?string $buyerAuthorizationCode = null;
    public ?string $siteId = null;
    public ?int $globalConfigVersion = null;
    public ?int $taxpayerConfigVersion = null;
    public ?int $terminalConfigVersion = null;
    public ?bool $isExport = null;
    public ?bool $isReliefSupply = null;
    public ?Vat5CertificateDto $vat5CertificateDetails = null;
    public ?string $paymentMethod = null;

    public function toArray(): array
    {
        return [
            'invoiceNumber' => $this->invoiceNumber instanceof self ? $this->invoiceNumber->toArray() : $this->invoiceNumber,
            'invoiceDateTime' => $this->invoiceDateTime instanceof self ? $this->invoiceDateTime->toArray() : $this->invoiceDateTime,
            'sellerTIN' => $this->sellerTIN instanceof self ? $this->sellerTIN->toArray() : $this->sellerTIN,
            'buyerTIN' => $this->buyerTIN instanceof self ? $this->buyerTIN->toArray() : $this->buyerTIN,
            'buyerName' => $this->buyerName instanceof self ? $this->buyerName->toArray() : $this->buyerName,
            'buyerAuthorizationCode' => $this->buyerAuthorizationCode instanceof self ? $this->buyerAuthorizationCode->toArray() : $this->buyerAuthorizationCode,
            'siteId' => $this->siteId instanceof self ? $this->siteId->toArray() : $this->siteId,
            'globalConfigVersion' => $this->globalConfigVersion instanceof self ? $this->globalConfigVersion->toArray() : $this->globalConfigVersion,
            'taxpayerConfigVersion' => $this->taxpayerConfigVersion instanceof self ? $this->taxpayerConfigVersion->toArray() : $this->taxpayerConfigVersion,
            'terminalConfigVersion' => $this->terminalConfigVersion instanceof self ? $this->terminalConfigVersion->toArray() : $this->terminalConfigVersion,
            'isExport' => $this->isExport instanceof self ? $this->isExport->toArray() : $this->isExport,
            'isReliefSupply' => $this->isReliefSupply instanceof self ? $this->isReliefSupply->toArray() : $this->isReliefSupply,
            'vat5CertificateDetails' => $this->vat5CertificateDetails instanceof self ? $this->vat5CertificateDetails->toArray() : $this->vat5CertificateDetails,
            'paymentMethod' => $this->paymentMethod instanceof self ? $this->paymentMethod->toArray() : $this->paymentMethod,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('invoiceNumber', $data)) {
            $instance->invoiceNumber = $data['invoiceNumber'];
        }
        if (array_key_exists('invoiceDateTime', $data)) {
            $instance->invoiceDateTime = $data['invoiceDateTime'];
        }
        if (array_key_exists('sellerTIN', $data)) {
            $instance->sellerTIN = $data['sellerTIN'];
        }
        if (array_key_exists('buyerTIN', $data)) {
            $instance->buyerTIN = $data['buyerTIN'];
        }
        if (array_key_exists('buyerName', $data)) {
            $instance->buyerName = $data['buyerName'];
        }
        if (array_key_exists('buyerAuthorizationCode', $data)) {
            $instance->buyerAuthorizationCode = $data['buyerAuthorizationCode'];
        }
        if (array_key_exists('siteId', $data)) {
            $instance->siteId = $data['siteId'];
        }
        if (array_key_exists('globalConfigVersion', $data)) {
            $instance->globalConfigVersion = $data['globalConfigVersion'];
        }
        if (array_key_exists('taxpayerConfigVersion', $data)) {
            $instance->taxpayerConfigVersion = $data['taxpayerConfigVersion'];
        }
        if (array_key_exists('terminalConfigVersion', $data)) {
            $instance->terminalConfigVersion = $data['terminalConfigVersion'];
        }
        if (array_key_exists('isExport', $data)) {
            $instance->isExport = $data['isExport'];
        }
        if (array_key_exists('isReliefSupply', $data)) {
            $instance->isReliefSupply = $data['isReliefSupply'];
        }
        if (array_key_exists('vat5CertificateDetails', $data)) {
            $instance->vat5CertificateDetails = $data['vat5CertificateDetails'];
        }
        if (array_key_exists('paymentMethod', $data)) {
            $instance->paymentMethod = $data['paymentMethod'];
        }
        return $instance;
    }
}
