<?php

namespace App\Providers;

use App\Models\Country;
use App\Models\ReceiptBranch;
use App\Models\ReceiptClient;
use App\Models\ReceiptCompany;
use App\Models\ReceiptOutgoing;
use App\Models\ReceiptPriceView;
use App\Models\ReceiptSocial;
use App\Observers\CountryObserver;
use App\Observers\ReceiptClientObserver;
use App\Observers\ReceiptCompanyObserver;
use App\Observers\ReceiptOutgoingObserver;
use App\Observers\ReceiptPriceViewObserver;
use App\Observers\ReceiptBranchObserver;
use App\Observers\ReceiptSocialObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        ReceiptSocial::observe(ReceiptSocialObserver::class);
        ReceiptBranch::observe(ReceiptBranchObserver::class);
        ReceiptClient::observe(ReceiptClientObserver::class);
        ReceiptOutgoing::observe(ReceiptOutgoingObserver::class);
        ReceiptCompany::observe(ReceiptCompanyObserver::class);
        ReceiptPriceView::observe(ReceiptPriceViewObserver::class);
        Country::observe(CountryObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
