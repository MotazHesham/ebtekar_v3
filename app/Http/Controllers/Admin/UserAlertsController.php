<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserAlertRequest;
use App\Http\Requests\StoreUserAlertRequest;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\DeviceToken;
use App\Models\User;
use App\Models\UserAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UserAlertsController extends Controller
{
    public function playlist(Request $request)
    {

        if ($request->ajax()) {
            $query = UserAlert::with(['users'])->where('type', 'playlist')->select(sprintf('%s.*', (new UserAlert)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('alert_text', function ($row) {
                return $row->alert_text ? $row->alert_text : '';
            });
            $table->editColumn('alert_link', function ($row) {
                return $row->alert_link ? $row->alert_link : '';
            });
            $table->rawColumns(['placeholder']);

            return $table->make(true);
        }

        return view('admin.userAlerts.playlist');
    }
    public function history(Request $request)
    {

        if ($request->ajax()) {
            $query = UserAlert::with(['users'])->where('type', 'history')->whereHas('users', function ($q) {
                $q->where('user_id', Auth::id());
            })->select(sprintf('%s.*', (new UserAlert)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('alert_text', function ($row) {
                return $row->alert_text ? $row->alert_text : '';
            });
            $table->editColumn('data', function ($row) {
                if ($row->data) {
                    $data = explode('&', $row->data);
                    return '<a class="btn btn-success btn-sm rounded-pill text-white"
                                onclick="show_details(' . $data[0] . ',\'' . $data[1] . '\')" >
                                أظهارالصور
                            </a>';
                } else {
                    return '';
                }
            });

            $table->rawColumns(['placeholder', 'data']);

            return $table->make(true);
        }

        return view('admin.userAlerts.history');
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_alert_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = UserAlert::where('type', 'public')->select(sprintf('%s.*', (new UserAlert)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'user_alert_show';
                $editGate      = 'user_alert_edit';
                $deleteGate    = 'user_alert_delete';
                $crudRoutePart = 'user-alerts';

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
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('alert_text', function ($row) {
                return $row->alert_text ? $row->alert_text : '';
            });
            $table->editColumn('alert_link', function ($row) {
                return $row->alert_link ? $row->alert_link : '';
            });
            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.userAlerts.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_alert_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userTypes = User::USER_TYPE_SELECT;

        return view('admin.userAlerts.create', compact('userTypes'));
    }

    public function usersByType(Request $request)
    {
        abort_if(Gate::denies('user_alert_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'user_type' => [
                'required',
                'string',
                'in:' . implode(',', array_keys(User::USER_TYPE_SELECT)),
            ],
        ]);

        $users = User::where('user_type', $request->user_type)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($users);
    }

    public function store(StoreUserAlertRequest $request)
    {
        $userType = $request->input('user_type');
        $recipientMode = $request->input('recipient_mode');

        $userAlert = UserAlert::create([
            'title' => $request->input('title'),
            'alert_text' => $request->input('alert_text'),
            'alert_link' => $request->input('alert_link'),
            'user_type' => $userType,
            'type' => 'public',
        ]);

        if ($recipientMode === 'all') {
            $userIds = User::where('user_type', $userType)->pluck('id')->all();
            $userAlert->users()->sync($userIds);
        } else {
            $userIds = $request->input('users', []);
            $userAlert->users()->sync($userIds);
        }

        if ($request->boolean('send_push_notification') && $userType === 'customer') {
            $this->dispatchPushNotifications($userAlert, $recipientMode, $userIds);
        }

        return redirect()->route('admin.user-alerts.index');
    }

    public function show(UserAlert $userAlert)
    {
        abort_if(Gate::denies('user_alert_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userAlert->load('users');

        return view('admin.userAlerts.show', compact('userAlert'));
    }

    public function destroy(UserAlert $userAlert)
    {
        abort_if(Gate::denies('user_alert_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userAlert->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserAlertRequest $request)
    {
        $userAlerts = UserAlert::find(request('ids'));

        foreach ($userAlerts as $userAlert) {
            $userAlert->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function read(Request $request)
    {
        $alerts = \Auth::user()->userUserAlerts()->where('read', false)->get();
        foreach ($alerts as $alert) {
            $pivot       = $alert->pivot;
            $pivot->read = true;
            $pivot->save();
        }
    }

    protected function dispatchPushNotifications(UserAlert $userAlert, string $recipientMode, array $userIds): void
    {
        if ($recipientMode === 'all') {
            SendFirebaseNotificationJob::dispatch(
                $this->buildFirebasePayload($userAlert),
                'customer',
                'all_users'
            );

            return;
        }

        DeviceToken::whereIn('user_id', $userIds)
            ->whereNotNull('device_token')
            ->where('device_token', '!=', '')
            ->each(function (DeviceToken $raw) use ($userAlert) {
                SendFirebaseNotificationJob::dispatch(
                    $this->buildFirebasePayload($userAlert, $raw->device_token),
                    'customer'
                );
            });
    }

    protected function buildFirebasePayload(UserAlert $userAlert, ?string $deviceToken = null): array
    {
        $payload = [
            'type' => 'user_alert',
            'id' => $userAlert->id,
            'title' => $userAlert->title,
            'text' => $userAlert->alert_text,
        ];

        if ($deviceToken) {
            $payload['device_token'] = $deviceToken;
        }

        if ($userAlert->alert_link) {
            $payload['data'] = json_encode(['alert_link' => $userAlert->alert_link]);
        }

        return $payload;
    }
}
