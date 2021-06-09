<?php

namespace Glasswalllab\WiiseConnector;

use glasswalllab\wiiseconnector\Jobs\CallWebService;
use Illuminate\Http\Request;

class WiiseConnector
{
    public function updateJobs()
    {  
        CallWebService::dispatch('/Job_List?\$select=No,Description,Bill_to_Customer_No,Status,Person_Responsible,Search_Description,Project_Manager','GET','');
        return "Updated Jobs";
    }

    //create resource

    //update resource

    //get job task lines

    //post job journal
}
