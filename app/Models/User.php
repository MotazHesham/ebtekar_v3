<?php

namespace App\Models;

use App\Jobs\SendVerificationMail;
use App\Mail\ResetPasswordMail;
use App\Mail\VerifyUserMail;
use App\Notifications\VerifyUserNotification;
use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface; 
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use SoftDeletes, Notifiable, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'users';

    protected $appends = [
        'photo',
    ];

    public static $searchable = [
        'phone_number',
        'address',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'verified_at',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const USER_TYPE_SELECT = [
        'customer'     => 'Customer',
        'staff'        => 'Staff',
        'delivery_man' => 'Delivery Man',
        'admin'        => 'Admin',
        'seller'       => 'Seller',
        'designer'       => 'Designer',
    ];

    protected $fillable = [
        'providerid',
        'name',
        'email',
        'phone_number',
        'address',
        'approved',
        'verified',
        'verified_at',
        'verification_token',
        'password',
        'user_type',
        'wasla_company_id',
        'wasla_token',
        'device_token',
        'email_verified_at',
        'remember_token',
        'website_setting_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::created(function (self $user) {
            if (auth()->check()) {
                $user->verified    = 1;
                $user->verified_at = Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'));
                $user->save();
            } elseif (!$user->verification_token) {
                $site_settings = get_site_setting(); 
                $token     = Str::random(64);
                $usedToken = self::where('verification_token', $token)->first();

                while ($usedToken) {
                    $token     = Str::random(64);
                    $usedToken = self::where('verification_token', $token)->first();
                }

                $user->verification_token = $token;
                $user->save(); 

                SendVerificationMail::dispatch($user,$site_settings,$user->email);  
            }
        });
    } 

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function userUserAlerts()
    {
        return $this->belongsToMany(UserAlert::class);
    }

    public function getVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setVerifiedAtAttribute($value)
    {
        $this->attributes['verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPasswordNotification($token,$site_settings)); 
        $site_settings = get_site_setting();
        $url = $site_settings->url . '/password/reset/' . $token . '?email=' . $this->email; 
        Mail::to($this->email)->send(new ResetPasswordMail($url,$site_settings)); 
    }

    public function getPhotoAttribute()
    {
        $file = $this->getMedia('photo')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    public function conversations_2()
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function designer()
    {
        return $this->hasOne(Designer::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function social_orders()
    {
        return $this->hasMany(Order::class, 'social_user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
    public function website()
    {
        return $this->belongsTo(WebsiteSetting::class,'website_setting_id');
    }

    public function hashedFirstName()
    {
        $Name = explode(' ',$this->name);
        if(isset($Name[0])){
            return hashedForConversionApi($Name[0]); 
        }else{
            return null;
        }
    }
    public function hashedLastName()
    {
        $Name = explode(' ',$this->name);
        if(isset($Name[1])){
            return hashedForConversionApi($Name[1]); 
        }else{
            return hashedForConversionApi('LN');
        }
    }
    public function hashedEmail()
    { 
        if($this->email){
            return hashedForConversionApi($this->email); 
        }else{
            return null;
        }
    }
    public function hashedPhone()
    { 
        if($this->phone_number){
            return hash('sha256', preg_replace('/\D/', '', $this->phone_number));
        }else{
            return null;
        }
    }
}
