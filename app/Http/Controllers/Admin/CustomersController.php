<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Customer::with(['user.website'])->select(sprintf('%s.*', (new Customer)->table))->with('website');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'customer_show';
                $editGate      = 'customer_edit';
                $deleteGate    = 'customer_delete';
                $crudRoutePart = 'customers';

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
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });
            $table->addColumn('user_email', function ($row) {
                return $row->user ? $row->user->email : '';
            });
            $table->addColumn('user_phone_number', function ($row) {
                return $row->user ? $row->user->phone_number : '';
            });
            $table->addColumn('user_address', function ($row) {
                return $row->user ? $row->user->address : '';
            });
            $table->addColumn('user_website_site_name', function ($row) {
                return $row->user->website ? $row->user->website->site_name : '';
            }); 
            $table->addColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.customers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.customers.create', compact('users' , 'websites'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'user_type' => 'customer',
            'approved' => 1,
            'website_setting_id' => $request->website_setting_id,
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
        ]);

        toast(trans('flash.global.success_title'),'success'); 
        return redirect()->route('admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customer->load('user');
        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 
        
        return view('admin.customers.edit', compact('customer', 'users','websites'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $user = User::find($customer->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]); 

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.customers.index');
    }

    public function show(Customer $customer)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->load('user');

        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->delete();

        alert(trans('flash.deleted'),'','success');

        return 1;
    }

    public function massDestroy(MassDestroyCustomerRequest $request)
    {
        $customers = Customer::find(request('ids'));

        foreach ($customers as $customer) {
            $customer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
