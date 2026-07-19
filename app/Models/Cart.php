<?php

namespace App\Models;

use App\Utils\CouponUtility;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;

    public $table = 'carts';

    protected $dates = [
        'created_at',
        'updated_at',
        'reminder_sent_at',
    ];

    protected $fillable = [
        'user_id',
        'temp_user_uid',
        'address_id',
        'store_id',
        'shipping_cost',
        'note',
        'coupon_code',
        'created_at',
        'updated_at',
        'reminder_sent_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function calculatedSubTotal()
    {
        $totalPrice = 0;
        foreach ($this->cartItems()->with('productStock', 'product')->get() as $cartItem) {
            $totalPrice += $cartItem->baseDiscountedCartPrice() * $cartItem->quantity;
        }
        return $totalPrice;
    }
}
