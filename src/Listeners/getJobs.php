<?php

namespace glasswalllab\wiiseconnector\Listeners;

use glasswalllab\wiiseconnector\Events\WebServiceResponse;

class getJobs
{
    public function handle(WebServiceResponse $event)
    {
        dd($event);
    }
}