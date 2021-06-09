<?php

namespace Glasswalllab\WiiseConnector;

use glasswalllab\wiiseconnector\Jobs\CallWebService;
use Illuminate\Http\Request;

class WiiseConnector 
{
    public function CallWebServiceSync($endpoint,$method,$body)
    {  
        $call = CallWebService::dispatchSync($endpoint,$method,$body);
        dd($call);
    }

    public function CallWebServiceQueue($endpoint,$method,$body)
    {  
        $call = CallWebService::dispatch($endpoint,$method,$body);
        dd($call);
    }
}
