<?php

namespace glasswalllab\wiiseconnector\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use glasswalllab\wiiseconnector\Events\WebServiceResponse;
use glasswalllab\wiiseconnector\Listeners\getJobs;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WebServiceResponse::class => [
            getJobs::class,
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