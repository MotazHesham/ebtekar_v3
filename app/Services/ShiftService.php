<?php

namespace App\Services;

use App\Models\EmployeeShift;
use App\Models\User;
use Carbon\Carbon;

class ShiftService
{
    public function getOpenCreatorShift(User $user): ?EmployeeShift
    {
        return EmployeeShift::where('user_id', $user->id)
            ->where('type', 'creator')
            ->where('status', 'open')
            ->latest('started_at')
            ->first();
    }

    public function openCreatorShift(User $user): EmployeeShift
    {
        return EmployeeShift::create([
            'user_id'    => $user->id,
            'type'       => 'creator',
            'status'     => 'open',
            'started_at' => date('Y-m-d H:i:s'),
            'shift_date' => date('Y-m-d'),
        ]);
    }

    public function closeCreatorShift(EmployeeShift $shift): EmployeeShift
    {
        if ($shift->status === 'closed') {
            return $shift;
        }

        $shift->update([
            'status'   => 'closed',
            'ended_at' => date('Y-m-d H:i:s'),
        ]);

        return $shift;
    }

    public function getOrCreateTodayOperationShift(User $user): EmployeeShift
    {
        $today = date('Y-m-d');

        $shift = EmployeeShift::where('user_id', $user->id)
            ->where('type', 'operation')
            ->where('shift_date', $today)
            ->first();

        if ($shift) {
            return $shift;
        }

        $startOfDay = date('Y-m-d 00:00:00');
        $endOfDay   = date('Y-m-d 23:59:59');

        return EmployeeShift::create([
            'user_id'    => $user->id,
            'type'       => 'operation',
            'status'     => 'closed',
            'started_at' => $startOfDay, 
            'ended_at'   => $endOfDay,
            'shift_date' => $today,
        ]);
    }
}

