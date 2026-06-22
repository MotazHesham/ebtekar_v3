<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\CalendarDateStoreRequest;
use App\Http\Requests\Api\V1\Customer\CalendarDateUpdateRequest;
use App\Http\Resources\V1\Customer\CalendarDateResource;
use App\Http\ResponseHelper;
use App\Models\CalendarDate;
use Illuminate\Http\Request;

class CalendarDateController extends Controller
{
    public function types()
    {
        $types = collect(CalendarDate::TYPE_SELECT)->map(function ($label, $key) {
            return [
                'key' => $key,
                'label' => $label,
            ];
        })->values();

        return ResponseHelper::returnResponse(trans('api.success.info'), [
            'types' => $types,
            'default_reminder_days_before' => (int) getSetting('calendar_default_reminder_days', 7),
        ]);
    }

    public function list()
    {
        $calendarDates = CalendarDate::where('user_id', auth()->user()->id)
            ->orderBy('event_date')
            ->get();

        return ResponseHelper::returnResource(CalendarDateResource::collection($calendarDates));
    }

    public function add(CalendarDateStoreRequest $request)
    {
        $calendarDate = CalendarDate::create([
            'user_id' => auth()->user()->id,
            'type' => $request->type,
            'description' => $request->description,
            'event_date' => $request->date,
            'reminder_days_before' => $request->reminder_days_before,
        ]);

        return ResponseHelper::returnResource(
            new CalendarDateResource($calendarDate),
            trans('api.success.success')
        );
    }

    public function update(CalendarDateUpdateRequest $request)
    {
        $calendarDate = CalendarDate::where('user_id', auth()->user()->id)
            ->findOrFail($request->calendar_date_id);

        $calendarDate->update([
            'type' => $request->type,
            'description' => $request->description,
            'event_date' => $request->date,
            'reminder_days_before' => $request->reminder_days_before,
        ]);

        return ResponseHelper::returnResource(
            new CalendarDateResource($calendarDate->fresh()),
            trans('api.success.updated')
        );
    }

    public function delete($id)
    {
        $calendarDate = CalendarDate::where('user_id', auth()->user()->id)
            ->findOrFail($id);

        $calendarDate->delete();

        return ResponseHelper::returnResponse(trans('api.success.deleted'));
    }
}
