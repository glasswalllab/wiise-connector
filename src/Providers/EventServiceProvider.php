<?php

namespace glasswalllab\wiiseconnector\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use glasswalllab\wiiseconnector\Events\ResponseReceived;
use glasswalllab\wiiseconnector\Listeners\UpdateResponse;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ResponseReceived::class => [
            UpdateResponse::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}