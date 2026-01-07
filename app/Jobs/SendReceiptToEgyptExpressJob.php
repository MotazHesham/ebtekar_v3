<?php

namespace App\Jobs;

use App\DTOs\EgyptExpressAirwayBillDTO;
use App\Services\EgyptExpressService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendReceiptToEgyptExpressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private EgyptExpressAirwayBillDTO $dto
    ) {}

    /**
     * Execute the job.
     */
    public function handle(EgyptExpressService $egyptExpressService): void
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/egyptexpress.log'),
            'level' => 'debug',
        ]);

        try {
            $logger->debug('egyptexpress:', [
                'action' => 'SendReceiptToEgyptExpressJob',
                'model_id' => $this->dto->modelId,
                'model_type' => $this->dto->modelType,
                'order_num' => $this->dto->orderNum,
                'status' => 'started',
            ]);

            // Validate DTO
            if (!$this->dto->isValid()) {
                $logger->warning('egyptexpress:', [
                    'action' => 'SendReceiptToEgyptExpressJob',
                    'model_id' => $this->dto->modelId,
                    'model_type' => $this->dto->modelType,
                    'order_num' => $this->dto->orderNum,
                    'status' => 'validation_failed',
                    'reason' => 'DTO validation failed',
                ]);
                return;
            }

            // Create airway bill
            $result = $egyptExpressService->createAirwayBill($this->dto);

            if ($result['success']) {
                // Update model with tracking information if available
                $this->updateModelWithTracking($result['data']);

                $logger->info('egyptexpress:', [
                    'action' => 'SendReceiptToEgyptExpressJob',
                    'model_id' => $this->dto->modelId,
                    'model_type' => $this->dto->modelType,
                    'order_num' => $this->dto->orderNum,
                    'status' => 'success',
                    'response' => $result['data'],
                ]);
            } else {
                $logger->error('egyptexpress:', [
                    'action' => 'SendReceiptToEgyptExpressJob',
                    'model_id' => $this->dto->modelId,
                    'model_type' => $this->dto->modelType,
                    'order_num' => $this->dto->orderNum,
                    'status' => 'failed',
                    'error' => $result['error'] ?? 'Unknown error',
                ]);

                // Re-throw exception to trigger retry mechanism
                throw new \Exception('Failed to create airway bill: ' . ($result['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $logger->error('egyptexpress:', [
                'action' => 'SendReceiptToEgyptExpressJob',
                'model_id' => $this->dto->modelId,
                'model_type' => $this->dto->modelType,
                'order_num' => $this->dto->orderNum,
                'status' => 'exception',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Update model with tracking information from response
     *
     * @param array $responseData
     * @return void
     */
    protected function updateModelWithTracking(array $responseData): void
    {
        if (!$this->dto->modelId || !$this->dto->modelType) {
            return;
        }

        // Find the model instance
        $model = $this->dto->modelType::find($this->dto->modelId);
        
        if (!$model) {
            return;
        }

        // Update tracking number if provided in response
        // Adjust these field names based on actual API response structure
        if (isset($responseData['AirWayBillNumber']) || isset($responseData['TrackingNumber'])) {
            $trackingNumber = $responseData['AirWayBillNumber'] ?? $responseData['TrackingNumber'] ?? null;
            
            if ($trackingNumber && isset($model->tracking_number)) {
                $model->update([
                    'tracking_number' => $trackingNumber,
                ]);
            }
        }

        // Update status if provided
        if (isset($responseData['Status']) && isset($model->status_code)) {
            // Map EgyptExpress status to your status codes if needed
            // $model->update(['status_code' => $responseData['Status']]);
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        $logger = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/egyptexpress.log'),
            'level' => 'debug',
        ]);

        $logger->error('egyptexpress:', [
            'action' => 'SendReceiptToEgyptExpressJob',
            'model_id' => $this->dto->modelId,
            'model_type' => $this->dto->modelType,
            'order_num' => $this->dto->orderNum,
            'status' => 'job_failed',
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
