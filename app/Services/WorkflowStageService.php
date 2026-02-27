<?php

namespace App\Services;

use App\Models\WorkflowOperation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkflowStageService
{
    protected ShiftService $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function startStage(Model $model, string $stage, ?User $user): WorkflowOperation
    {
        $operation = WorkflowOperation::firstOrCreate(
            [
                'model_type' => get_class($model),
                'model_id'   => $model->id,
                'stage'      => $stage,
            ],
            [
                'user_id'    => $user?->id,
                'status'     => 'in_progress',
                'started_at' => date('Y-m-d H:i:s'),
            ]
        );

        if ($operation->status !== 'in_progress') {
            $operation->update([
                'user_id'    => $user?->id,
                'status'     => 'in_progress',
                'started_at' => date('Y-m-d H:i:s'),
                'ended_at'   => null,
                'shift_id'   => null,
            ]);
        } 
        return $operation;
    }

    public function completeStage(Model $model, string $stage, User $user): ?WorkflowOperation
    {
        $operation = WorkflowOperation::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->where('stage', $stage)
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest('started_at')
            ->first();

        if (! $operation) {
            return null;
        }

        $shift = $this->shiftService->getOrCreateTodayOperationShift($user);

        $operation->update([
            'user_id'  => $user->id,
            'shift_id' => $shift->id,
            'ended_at' => date('Y-m-d H:i:s'),
            'status'   => 'completed',
        ]);

        return $operation;
    }

    public function moveToNextStage(Model $model, string $fromStage, ?string $toStage, ?User $nextUser): void
    { 
        if ($fromStage) {
            $this->completeStage($model, $fromStage, auth()->user());
        }

        if ($toStage) {
            $this->startStage($model, $toStage, $nextUser);
        }
    }
}

