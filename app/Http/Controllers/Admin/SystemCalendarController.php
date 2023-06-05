<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SystemCalendarController extends Controller
{
    public $sources = [
        [
            'model'      => '\App\Models\Order',
            'date_field' => 'excepected_deliverd_date',
            'field'      => 'order_num',
            'prefix'     => '',
            'suffix'     => 'expected delivery',
            'route'      => 'admin.orders.edit',
        ],
        [
            'model'      => '\App\Models\ReceiptSocial',
            'date_field' => 'deliver_date',
            'field'      => 'order_num',
            'prefix'     => '',
            'suffix'     => 'expected delivery',
            'route'      => 'admin.receipt-socials.edit',
        ],
        [
            'model'      => '\App\Models\ReceiptCompany',
            'date_field' => 'deliver_date',
            'field'      => 'order_num',
            'prefix'     => '',
            'suffix'     => 'expected delivery',
            'route'      => 'admin.receipt-companies.edit',
        ],
        [
            'model'      => '\App\Models\Task',
            'date_field' => 'due_date',
            'field'      => 'name',
            'prefix'     => '',
            'suffix'     => 'deadline',
            'route'      => 'admin.tasks.edit',
        ],
    ];

    public function index()
    {
        $events = [];
        foreach ($this->sources as $source) {
            foreach ($source['model']::all() as $model) {
                $crudFieldValue = $model->getAttributes()[$source['date_field']];

                if (! $crudFieldValue) {
                    continue;
                }

                $events[] = [
                    'title' => trim($source['prefix'] . ' ' . $model->{$source['field']} . ' ' . $source['suffix']),
                    'start' => $crudFieldValue,
                    'url'   => route($source['route'], $model->id),
                ];
            }
        }

        return view('admin.calendar.calendar', compact('events'));
    }
}
