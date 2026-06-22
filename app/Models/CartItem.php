<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    public $table = 'cart_items';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_stock_id',
        'quantity',
        'variant',
        'description',
        'photos',
        'pdf',
        'link',
        'email_sent',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }

    public function baseCartPrice()
    {
        if ($this->productStock) {
            return $this->productStock->basePrice($this->product);
        } else {
            return $this->product->basePrice($this->product);
        }
    }

    public function baseDiscountedCartPrice()
    {
        if ($this->productStock) {
            return $this->productStock->baseDiscountedPrice($this->product);
        } else {
            return $this->product->baseDiscountedPrice($this->product);
        }
    }
}
