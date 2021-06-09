<?php

namespace glasswalllab\wiiseconnector\Listeners;

use glasswalllab\wiiseconnector\Events\ResponseReceived;

class UpdateResponse
{
    public function handle(ResponseReceived $event)
    {
        dd(json_decode($event->getBody()->getContents()));
    }
}