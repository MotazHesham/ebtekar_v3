<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EgyptExpressAirwayBill;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EgyptExpressAirwayBillController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = EgyptExpressAirwayBill::query()
                ->with('model')
                ->select(sprintf('%s.*', (new EgyptExpressAirwayBill)->table));
            
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'egyptexpress_airway_bill_show';
                $editGate      = 'egyptexpress_airway_bill_edit';
                $deleteGate    = 'egyptexpress_airway_bill_delete';
                $crudRoutePart = 'egyptexpress-airway-bills';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            $table->editColumn('shipper_reference', function ($row) {
                return $row->shipper_reference ? $row->shipper_reference : '';
            });

            $table->editColumn('order_num', function ($row) {
                return $row->order_num ? $row->order_num : '';
            });

            $table->editColumn('airway_bill_number', function ($row) {
                return $row->airway_bill_number ? $row->airway_bill_number : '-';
            });

            $table->editColumn('tracking_number', function ($row) {
                return $row->tracking_number ? $row->tracking_number : '-';
            });

            $table->editColumn('receiver_name', function ($row) {
                return $row->receiver_name ? $row->receiver_name : '';
            });

            $table->editColumn('receiver_phone', function ($row) {
                return $row->receiver_phone ? $row->receiver_phone : '';
            });

            $table->editColumn('receiver_city', function ($row) {
                return $row->receiver_city ? $row->receiver_city : '';
            });

            $table->editColumn('destination', function ($row) {
                return $row->destination ? $row->destination : '';
            });

            $table->editColumn('is_successful', function ($row) {
                return $row->is_successful 
                    ? '<span class="badge bg-success">Success</span>' 
                    : '<span class="badge bg-danger">Failed</span>';
            });

            $table->editColumn('model_type', function ($row) {
                $modelType = class_basename($row->model_type);
                return $modelType ? $modelType : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format(config('panel.date_format') . ' ' . config('panel.time_format')) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'is_successful']);

            return $table->make(true);
        }

        return view('admin.egyptexpressAirwayBills.index');
    }

    public function show(EgyptExpressAirwayBill $egyptexpressAirwayBill)
    {

        $egyptexpressAirwayBill->load('model');

        return view('admin.egyptexpressAirwayBills.show', compact('egyptexpressAirwayBill'));
    }
}
