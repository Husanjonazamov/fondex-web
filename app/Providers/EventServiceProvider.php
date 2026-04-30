<?php

namespace App\Providers;

use App\Events\OrderPaymentSucceeded;
use App\Events\PaymentSucceeded;
use App\Listeners\OrderPaymentSucceededListener;
use App\Listeners\PaymentSucceededListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PaymentSucceeded::class => [
            PaymentSucceededListener::class,
        ],
        OrderPaymentSucceeded::class => [
            OrderPaymentSucceededListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}