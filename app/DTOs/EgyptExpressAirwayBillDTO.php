<?php

namespace App\DTOs;

class EgyptExpressAirwayBillDTO
{
    public function __construct(
        // Receiver Information
        public string $receiverName,
        public string $receiverPhone,
        public ?string $receiverPhone2 = null,
        public ?string $receiverEmail = null,
        public string $receiverAddress1,
        public ?string $receiverAddress2 = null,
        public string $receiverCity,
        public ?string $receiverProvince = null,
        public ?string $receiverSubCity = null,
        public ?string $receiverZip = null,
        public ?string $receiverCompany = null,
        public string $receiverCountry = 'Egypt',
        
        // Shipping Information
        public string $destination,
        public string $origin = 'CAI',
        public int $numberOfPieces = 1,
        public float $weight = 1.0,
        public string $goodsDescription = 'Mobile Accessories',
        public string $serviceType = 'FRG',
        public string $productType = 'FRE',
        
        // COD Information
        public float $codAmount = 0.0,
        public string $codCurrency = '',
        
        // Invoice Information
        public float $invoiceValue = 0.0,
        public string $invoiceCurrency = 'EGP',
        
        // Reference and Instructions
        public string $shipperReference,
        public ?string $specialInstruction = null,
        
        // Additional fields
        public ?int $modelId = null,
        public ?string $modelType = null,
        public ?string $orderNum = null,
    ) {}

    /**
     * Convert DTO to array format for EgyptExpress API
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'AirWayBillCreatedBy' => 'Ebtekar System',
            'CODAmount' => $this->codAmount,
            'CODCurrency' => $this->codCurrency,
            'Destination' => $this->destination,
            'DutyConsigneePay' => 0,
            'GoodsDescription' => mb_substr($this->goodsDescription, 0, 100),
            'NumberofPeices' => $this->numberOfPieces,
            'Origin' => $this->origin,
            'ProductType' => $this->productType,
            'ReceiversAddress1' => $this->receiverAddress1,
            'ReceiversAddress2' => $this->receiverAddress2 ?? '',
            'ReceiversCity' => $this->receiverCity,
            'ReceiversCompany' => $this->receiverCompany ?? '',
            'ReceiversContactPerson' => $this->receiverName,
            'ReceiversCountry' => $this->receiverCountry,
            'ReceiversEmail' => $this->receiverEmail ?? '',
            'ReceiversGeoLocation' => '',
            'ReceiversMobile' => $this->receiverPhone,
            'ReceiversPhone' => $this->receiverPhone2 ?? $this->receiverPhone,
            'ReceiversPinCode' => $this->receiverZip ?? '',
            'ReceiversProvince' => $this->receiverProvince ?? '',
            'ReceiversSubCity' => $this->receiverSubCity ?? '',
            'SendersAddress1' => config('services.egyptexpress.sender.address1'),
            'SendersAddress2' => config('services.egyptexpress.sender.address2'),
            'SendersCity' => config('services.egyptexpress.sender.city'),
            'SendersCompany' => config('services.egyptexpress.sender.company'),
            'SendersContactPerson' => config('services.egyptexpress.sender.contact_person'),
            'SendersCountry' => 'Egypt',
            'SendersEmail' => config('services.egyptexpress.sender.email'),
            'SendersGeoLocation' => '',
            'SendersMobile' => config('services.egyptexpress.sender.mobile'),
            'SendersPhone' => config('services.egyptexpress.sender.phone'),
            'SendersPinCode' => '',
            'SendersSubCity' => '',
            'ServiceType' => $this->serviceType,
            'ShipmentDimension' => '',
            'ShipmentInvoiceCurrency' => $this->invoiceCurrency,
            'ShipmentInvoiceValue' => $this->invoiceValue,
            'ShipperReference' => $this->shipperReference,
            'ShipperVatAccount' => '',
            'SpecialInstruction' => $this->specialInstruction ?? '',
            'Weight' => $this->weight,
        ];
    }

    /**
     * Validate required fields
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->receiverName)
            && !empty($this->receiverPhone)
            && !empty($this->receiverAddress1)
            && !empty($this->receiverCity)
            && !empty($this->destination)
            && !empty($this->shipperReference);
    }
}
