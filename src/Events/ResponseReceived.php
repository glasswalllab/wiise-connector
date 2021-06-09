<?php

namespace glasswalllab\wiiseconnector\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ResponseReceived
{
    use Dispatchable, SerializesModels;

    public $response;

    public function __construct($response)
    {
        $this->response = $response;
    }
}