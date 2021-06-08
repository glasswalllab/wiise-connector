<?php

namespace Glasswalllab\WiiseConnector;

use glasswalllab\wiiseconnector\Jobs\CallWebService;
use Illuminate\Http\Request;

class WiiseConnector
{
    public function getjobs()
    {  
        return($this->callwebservice('/Job_List?\$select=No,Description,Bill_to_Customer_No,Status,Person_Responsible,Search_Description,Project_Manager','GET','')->value);
    }

    //create resource

    //update resource

    //get job task lines

    //post job journal

    private function callwebservice($endpoint,$method,$body)
    {
        CallWebService::dispatch($endpoint,$method,$body);
    }
}
