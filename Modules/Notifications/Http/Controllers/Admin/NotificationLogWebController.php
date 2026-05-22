<?php

namespace Modules\Notifications\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Modules\Notifications\Entities\NotificationDelivery;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NotificationLogWebController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('delivery_notifications_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NotificationDelivery::query()
                ->with(['shipment', 'user'])
                ->orderByDesc('id');

            if ($request->filled('channel')) {
                $query->where('channel', $request->channel);
            }
            if ($request->filled('event_type')) {
                $query->where('event_type', 'like', '%' . $request->event_type . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $table = Datatables::of($query);
            $table->addColumn('order_num', fn ($row) => $row->shipment?->order_num ?? '—');
            $table->addColumn('user_name', fn ($row) => $row->user?->name ?? '—');
            $table->editColumn('status', fn ($row) => '<span class="badge badge-secondary">' . e($row->status_label) . '</span>');
            $table->editColumn('created_at', fn ($row) => $row->created_at
                ? Carbon::parse($row->created_at)->format(config('panel.date_format') . ' ' . config('panel.time_format'))
                : '—');
            $table->rawColumns(['status']);

            return $table->make(true);
        }

        return view('notifications::admin.log');
    }
}
