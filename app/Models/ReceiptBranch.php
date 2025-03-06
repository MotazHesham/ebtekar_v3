<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptBranch extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'receipt_branches';

    public static $searchable = [
        'order_num',
        'client_name',
        'phone_number',
    ];

    public const PERMISSION_STATUS_SELECT = [
        'deliverd' => 'تم تسليم الأوردر',
        'receive_premission'   => 'تم استلام الأذن',
        'permission_segment'   => 'تم تجزئة الأذن',
        'permission_complete'   => 'تم صرف الأذن',
        'permission_complete_2'   => 'تم تسليم الأذن',
    ];

    protected $dates = [
        'date_of_receiving_order',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date_of_receiving_order',
        'order_num',
        'client_name',
        'phone_number',
        'deposit',
        'discount',
        'note',
        'total_cost',
        'done',
        'quickly',
        'printing_times',
        'permission_status',
        'staff_id',
        'website_setting_id',
        'r_branch_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function receiptsReceiptBranchProducts()
    {
        return $this->hasMany(ReceiptBranchProductPivot::class,'receipt_branch_id');
    }

    public function getDateOfReceivingOrderAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateOfReceivingOrderAttribute($value)
    {
        $this->attributes['date_of_receiving_order'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function branch()
    {
        return $this->belongsTo(RBranch::class, 'r_branch_id');
    }

    public function incomes()
    {
        return $this->morphMany(Income::class, 'model');
    }

    public function expenses()
    {
        return $this->morphMany(Expense::class, 'model');
    }

    public function website(){
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }
	// operations
	public function calc_discount(){ 
		return $this->total_cost - $this->discount;
	}

	public function calc_total_cost(){
		return $this->total_cost - $this->calc_discount();
	}

	public function calc_total_for_client(){
		return $this->total_cost - $this->calc_discount()  - $this->deposit;
	}

    public function add_income(){ 
        if($this->branch->type == 'income'){
            Income::create([ 
                'income_category_id' => 1,
                'entry_date' => date(config('panel.date_format')),
                'amount' => $this->calc_total_cost(),
                'description' => '',
                'model_id' => $this->id,
                'model_type' => 'App\Models\ReceiptBranch',
            ]);
        }else{
            Expense::create([ 
                'expense_category_id' => 15,
                'entry_date' => date(config('panel.date_format')),
                'amount' => $this->calc_total_cost(),
                'description' => '',
                'model_id' => $this->id,
                'model_type' => 'App\Models\ReceiptBranch',
            ]);

        }
        return 1;
    }

    public function price_type(){ 
        if($this->branch){
            $zz =  $this->branch->payment_type == 'permissions_parts' ? 'permissions' : $this->branch->payment_type;
        }else{
            $zz = '';
        }
        $payment_type = $this->branch ?  '_' . $zz : '';
        return $payment_type == '_cash' ? 'price'  : 'price'. $payment_type;
    }
}
