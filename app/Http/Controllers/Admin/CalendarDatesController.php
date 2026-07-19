<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCalendarDateRequest;
use App\Models\CalendarDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CalendarDatesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('calendar_date_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CalendarDate::with(['user'])->select(sprintf('%s.*', (new CalendarDate)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'calendar_date_show';
                $editGate      = null;
                $deleteGate    = 'calendar_date_delete';
                $crudRoutePart = 'calendar-dates';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', fn($row) => $row->id ?? '');
            $table->addColumn('user_name', fn($row) => $row->user?->name ?? '');
            $table->addColumn('user_phone', fn($row) => $row->user?->phone_number ?? '');
            $table->addColumn('type_label', fn($row) => $row->type_label);
            $table->editColumn('description', fn($row) => $row->description ?? '');
            $table->editColumn('event_date', fn($row) => $row->event_date ?? '');
            $table->addColumn('reminder_days', fn($row) => $row->effective_reminder_days_before);
            $table->addColumn('uses_default_reminder', function ($row) {
                return is_null($row->reminder_days_before)
                    ? __('cruds.calendarDate.fields.uses_default_reminder')
                    : __('cruds.calendarDate.fields.custom_reminder');
            });
            $table->editColumn('created_at', fn($row) => $row->created_at ?? '');

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.calendarDates.index');
    }

    public function show(CalendarDate $calendarDate)
    {
        abort_if(Gate::denies('calendar_date_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $calendarDate->load('user');

        return view('admin.calendarDates.show', compact('calendarDate'));
    }

    public function destroy(CalendarDate $calendarDate)
    {
        abort_if(Gate::denies('calendar_date_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $calendarDate->delete();

        alert(__('flash.deleted'), '', 'success');

        return 1;
    }

    public function massDestroy(MassDestroyCalendarDateRequest $request)
    {
        $calendarDates = CalendarDate::find(request('ids'));

        foreach ($calendarDates as $calendarDate) {
            $calendarDate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
