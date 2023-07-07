<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCommissionRequestRequest;
use App\Http\Requests\StoreCommissionRequestRequest;
use App\Http\Requests\UpdateCommissionRequestRequest;
use App\Models\CommissionRequest;
use App\Models\CommissionRequestOrders;
use App\Models\Order;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CommissionRequestsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('commission_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CommissionRequest::with(['user', 'created_by', 'done_by_user','commission_request_orders.order'])->select(sprintf('%s.*', (new CommissionRequest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {  
                $view      = '';
                $edit      = '';
                $delete    = '';
                if(Gate::allows('commission_request_show')){ 
                    $view = '<a class="btn btn-xs btn-primary" href="'.route('admin.commission-requests.show', $row->id).'">
                                '. trans("global.view").'
                            </a>';
                } 
                if($row->status == 'requested'){
                    if(Gate::allows('commission_request_edit')){
                        $edit  = '<a class="btn btn-xs btn-info" href="'.route('admin.commission-requests.edit', $row->id).'">
                                    '. trans("global.pay").'
                                </a>';
                    }
                    if(Gate::allows('commission_request_delete')){ 
                        $route = route('admin.commission-requests.destroy', $row->id);
                        $delete = '<a class="btn btn-xs btn-danger" href="#" onclick="deleteConfirmation('.$route.')">
                                    '. trans("global.delete").'
                                </a>';
                    } 
    
                }
                return $view . $edit . $delete;
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? CommissionRequest::STATUS_SELECT[$row->status] : '';
            }); 
            $table->editColumn('payment_method', function ($row) {
                return $row->payment_method ? CommissionRequest::PAYMENT_METHOD_SELECT[$row->payment_method] : '';
            });
            $table->editColumn('transfer_number', function ($row) {
                return $row->transfer_number ? $row->transfer_number : '';
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            }); 
            $table->addColumn('orders', function ($row) {
                $str = '';
                foreach($row->commission_request_orders as $request_order){ 
                    $str .= '<span class="badge badge-dark">'. $request_order->order->order_num .'</span>';
                    $str .= '<span class="badge badge-warning">' .$request_order->commission .'</span><br>'; 
                }
                $str .= '<span class="badge badge-success">المجموع :'. $row->total_commission .'</span>';
                return $str;
            }); 

            $table->addColumn('done', function ($row) { 
                $done_by_user = $row->done_by_user ? '<span class="badge badge-danger">بواسطة :'.$row->done_by_user->name.'<span><br>' . $row->done_time : '';
                return $done_by_user;
            });

            $table->rawColumns(['actions', 'placeholder', 'user' , 'done','orders']);

            return $table->make(true);
        }

        return view('admin.commissionRequests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('commission_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $done_by_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.commissionRequests.create', compact('created_bies', 'done_by_users', 'users'));
    }

    public function store(StoreCommissionRequestRequest $request)
    {
        $commissionRequest = CommissionRequest::create($request->all());

        return redirect()->route('admin.commission-requests.index');
    }

    public function edit(CommissionRequest $commissionRequest)
    {
        abort_if(Gate::denies('commission_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $commission_request_orders = CommissionRequestOrders::where('commission_request_id', $commissionRequest->id)->get();
        foreach($commission_request_orders as $raw){
            $order = Order::find($raw->order_id);
            $order->commission_status = 'delivered';
            $order->save();
            
        }
        $commissionRequest->status = 'delivered';
        $commissionRequest->done_by_user_id = Auth::user()->id;
        $commissionRequest->done_time = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $commissionRequest->save();  
        alert('Paid successfully','','success');
        return redirect()->route('admin.commission-requests.index');
    }

    public function update(UpdateCommissionRequestRequest $request, CommissionRequest $commissionRequest)
    {
        $commissionRequest->update($request->all());

        return redirect()->route('admin.commission-requests.index');
    }

    public function show(CommissionRequest $commissionRequest)
    {
        abort_if(Gate::denies('commission_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commissionRequest->load('user', 'created_by', 'done_by_user');

        return view('admin.commissionRequests.show', compact('commissionRequest'));
    }

    public function destroy(CommissionRequest $commissionRequest)
    {
        abort_if(Gate::denies('commission_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commissionRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyCommissionRequestRequest $request)
    {
        $commissionRequests = CommissionRequest::find(request('ids'));

        foreach ($commissionRequests as $commissionRequest) {
            $commissionRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
