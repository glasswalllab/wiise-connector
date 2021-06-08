<?php

namespace glasswalllab\wiiseconnector\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use JohnDoe\BlogPackage\Models\Post;

class WebServiceResponse
{
    use Dispatchable, SerializesModels;

    public $response;

    public function __construct(Request $response)
    {
        $this->response = $response;
    }
}