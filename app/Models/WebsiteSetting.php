<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WebsiteSetting extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'website_settings';

    protected $appends = [
        'logo',
        'photos',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // places to edit if add a new website
    // 1 - [order_num] in receipts observers (receipt-socials / receipt-client / receipt-branch / receipt-price-view ) && checkoutController to generate order_num for orders
    // 2 - site_title in [ resources => lang => panel ]
    // 3 - footer about the site in [ frontend => partials => footer ]
    // 4 - in db add colors.css
    // 5 - in table of  receipts   (order-num) different colors
    // 6 - in frontend top-navbar links to other websites

    public const DELIVERY_SYSTEM_SELECT = [
        'ebtekar' => 'Ebtekar',
        'wasla'   => 'Wasla',
    ];
    public const WEBSITES = [
        "" => 'ابتكار',
        1 => 'ابتكار',
        2   => 'ارتجال',
        3   => 'فيجرز',
        4   => 'novi',
        5   => 'مارتوبيا',
        6   => 'a1 digital',
        7   => 'Ein',
    ];

    protected $fillable = [
        'site_name',
        'css_file_name',
        'domains',
        'url',
        'description_seo',
        'keywords_seo',
        'author_seo',
        'sitemap_link_seo',
        'address',
        'description',
        'phone_number',
        'email',
        'facebook',
        'instagram',
        'twitter',
        'telegram',
        'linkedin',
        'whatsapp',
        'youtube',
        'google_analytics',
        'tag_manager',
        'fb_pixel_id',
        'fb_access_token',
        'fb_test_code',
        'shopify_api_key',
        'shopify_access_token',
        'shopify_domain',
        'shopify_api_secret',
        'shopify_webhook_sign',
        'shopify_integration_status',
        'order_num_prefix',
        'playlist_status',
        'google_plus',
        'welcome_message',
        'video_instructions',
        'delivery_system',
        'borrow_password',
        'shipping_integration',
        'designer_id',
        'preparer_id',
        'manufacturer_id',
        'shipmenter_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getLogoAttribute()
    {
        $file = $this->getMedia('logo')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getPhotosAttribute()
    {
        $files = $this->getMedia('photos');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function preparer()
    {
        return $this->belongsTo(User::class, 'preparer_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(User::class, 'manufacturer_id');
    }

    public function shipment()
    {
        return $this->belongsTo(User::class, 'shipmenter_id');
    }
}
