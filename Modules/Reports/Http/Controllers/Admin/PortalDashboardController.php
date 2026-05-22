<?php

namespace Modules\Reports\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Reports\Services\ShippingReportService;

class PortalDashboardController extends Controller
{
    public function __construct(protected ShippingReportService $reports)
    {
    }

    public function home()
    {
        return redirect()->route('admin.shipping.dashboard.' . $this->reports->resolveRole());
    }

    public function partner(Request $request)
    {
        abort_unless($this->reports->resolveRole() === 'partner', 403);

        return view('reports::admin.dashboard', [
            'dashboard' => $this->reports->partnerDashboard(auth()->user(), $request->date_from, $request->date_to),
            'title'     => __('reports::titles.partner'),
        ]);
    }

    public function courier(Request $request)
    {
        abort_unless($this->reports->resolveRole() === 'courier', 403);

        return view('reports::admin.dashboard', [
            'dashboard' => $this->reports->courierDashboard(auth()->user(), $request->date_from, $request->date_to),
            'title'     => __('reports::titles.courier'),
        ]);
    }

    public function dispatcher(Request $request)
    {
        abort_unless($this->reports->resolveRole() === 'dispatcher', 403);

        return view('reports::admin.dashboard', [
            'dashboard' => $this->reports->dispatcherDashboard(auth()->user(), $request->date_from, $request->date_to),
            'title'     => __('reports::titles.dispatcher'),
        ]);
    }

    public function admin(Request $request)
    {
        abort_unless(in_array($this->reports->resolveRole(), ['admin'], true), 403);
        abort_unless(auth()->user()->can('delivery_reports_access') || auth()->user()->is_admin, 403);

        return view('reports::admin.dashboard', [
            'dashboard' => $this->reports->adminDashboard(auth()->user(), $request->date_from, $request->date_to),
            'title'     => __('reports::titles.admin'),
        ]);
    }
}
