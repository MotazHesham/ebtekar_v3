<?php

namespace App\Services;

use App\DTOs\EgyptExpressAirwayBillDTO;
use App\Models\EgyptExpressAirwayBill;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EgyptExpressService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected string $accountNo;

    public function __construct()
    {
        $this->baseUrl = config('services.egyptexpress.base_url');
        $this->username = config('services.egyptexpress.username');
        $this->password = config('services.egyptexpress.password');
        $this->accountNo = config('services.egyptexpress.account_no');
    }

    /**
     * Create an airway bill using DTO
     *
     * @param EgyptExpressAirwayBillDTO $dto
     * @return array
     */
    public function createAirwayBill(EgyptExpressAirwayBillDTO $dto): array
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/egyptexpress.log'),
            'level' => 'debug',
        ]);

        try {
            // Validate DTO
            if (!$dto->isValid()) {
                $logger->error('egyptexpress:', [
                    'action' => 'CreateAirwayBill',
                    'status' => 'validation_failed',
                    'reason' => 'DTO validation failed',
                ]);

                return [
                    'success' => false,
                    'error' => 'Invalid DTO data: missing required fields',
                ];
            }

            $airwayBillData = $dto->toArray();

            $payload = [
                'UserName' => $this->username,
                'Password' => $this->password,
                'AccountNo' => $this->accountNo,
                'AirwayBillData' => $airwayBillData,
            ];

            $logger->debug('egyptexpress:', [
                'action' => 'CreateAirwayBill',
                'model_id' => $dto->modelId,
                'model_type' => $dto->modelType,
                'order_num' => $dto->orderNum,
                'payload' => $payload,
            ]);

            $response = Http::timeout(30)
                ->post("{$this->baseUrl}/CreateAirwayBill", $payload);

            $responseData = $response->json();

            // Check both HTTP status and API response code
            // API returns Code field: negative values indicate errors
            $apiCode = $responseData['Code'] ?? $responseData['code'] ?? null;
            $isApiSuccess = $apiCode !== null ? ($apiCode >= 0) : true;
            $isHttpSuccess = $response->successful();
            $isSuccess = $isHttpSuccess && $isApiSuccess;

            if ($isSuccess) {
                // Save successful airway bill record
                $airwayBill = $this->saveAirwayBill($dto, $payload, $responseData, true, null, $response->status());

                $logger->debug('egyptexpress:', [
                    'action' => 'CreateAirwayBill',
                    'model_id' => $dto->modelId,
                    'model_type' => $dto->modelType,
                    'order_num' => $dto->orderNum,
                    'airway_bill_id' => $airwayBill->id,
                    'response' => $responseData,
                    'status' => 'success',
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'airway_bill_id' => $airwayBill->id,
                ];
            } else {
                // Save failed airway bill record
                // Extract error message from Description or message field
                $errorMessage = $responseData['Description'] 
                    ?? $responseData['description'] 
                    ?? $responseData['message'] 
                    ?? $responseData['Message']
                    ?? 'Unknown error';
                
                $this->saveAirwayBill($dto, $payload, $responseData, false, $errorMessage, $response->status());

                $logger->error('egyptexpress:', [
                    'action' => 'CreateAirwayBill',
                    'model_id' => $dto->modelId,
                    'model_type' => $dto->modelType,
                    'order_num' => $dto->orderNum,
                    'status_code' => $response->status(),
                    'api_code' => $apiCode,
                    'response' => $responseData,
                    'status' => 'failed',
                ]);

                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'status_code' => $response->status(),
                    'api_code' => $apiCode,
                    'data' => $responseData,
                ];
            }
        } catch (\Exception $e) {
            // Save failed airway bill record for exceptions
            try {
                $this->saveAirwayBill($dto, $payload ?? null, null, false, $e->getMessage(), null);
            } catch (\Exception $saveException) {
                // Log but don't fail if saving the record fails
                $logger->error('egyptexpress:', [
                    'action' => 'CreateAirwayBill',
                    'error' => 'Failed to save airway bill record: ' . $saveException->getMessage(),
                ]);
            }

            $logger->error('egyptexpress:', [
                'action' => 'CreateAirwayBill',
                'model_id' => $dto->modelId,
                'model_type' => $dto->modelType,
                'order_num' => $dto->orderNum,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Save airway bill record to database
     *
     * @param EgyptExpressAirwayBillDTO $dto
     * @param array|null $requestPayload
     * @param array|null $responseData
     * @param bool $isSuccessful
     * @param string|null $errorMessage
     * @param int|null $httpStatusCode
     * @return EgyptExpressAirwayBill
     */
    protected function saveAirwayBill(
        EgyptExpressAirwayBillDTO $dto,
        ?array $requestPayload,
        ?array $responseData,
        bool $isSuccessful,
        ?string $errorMessage = null,
        ?int $httpStatusCode = null
    ): EgyptExpressAirwayBill {
        // Extract tracking information from response
        $airwayBillNumber = null;
        $trackingNumber = null;
        $status = null;
        $statusDescription = null;

        if ($responseData) {
            $airwayBillNumber = $responseData['AirWayBillNumber'] 
                ?? $responseData['airway_bill_number'] 
                ?? $responseData['AirwayBillNumber'] 
                ?? null;
            
            $trackingNumber = $responseData['TrackingNumber'] 
                ?? $responseData['tracking_number'] 
                ?? $airwayBillNumber 
                ?? null;
            
            $status = $responseData['Status'] 
                ?? $responseData['status'] 
                ?? null;
            
            $statusDescription = $responseData['StatusDescription'] 
                ?? $responseData['status_description'] 
                ?? $responseData['Message'] 
                ?? $responseData['message'] 
                ?? null;
        }

        return EgyptExpressAirwayBill::create([
            'model_type' => $dto->modelType,
            'model_id' => $dto->modelId,
            'shipper_reference' => $dto->shipperReference,
            'order_num' => $dto->orderNum,
            'airway_bill_number' => $airwayBillNumber,
            'tracking_number' => $trackingNumber,
            'status' => $status,
            'status_description' => $statusDescription,
            'receiver_name' => $dto->receiverName,
            'receiver_phone' => $dto->receiverPhone,
            'receiver_city' => $dto->receiverCity,
            'destination' => $dto->destination,
            'number_of_pieces' => $dto->numberOfPieces,
            'weight' => $dto->weight,
            'goods_description' => $dto->goodsDescription,
            'cod_amount' => $dto->codAmount,
            'cod_currency' => $dto->codCurrency,
            'invoice_value' => $dto->invoiceValue,
            'invoice_currency' => $dto->invoiceCurrency,
            'request_payload' => $requestPayload,
            'response_data' => $responseData,
            'is_successful' => $isSuccessful,
            'error_message' => $errorMessage,
            'http_status_code' => $httpStatusCode,
        ]);
    }


    // TODO: Add more methods for other endpoints as needed
    // Example:
    // public function trackShipment(string $trackingNumber): array
    // public function cancelAirwayBill(string $airwayBillNumber): array
}
