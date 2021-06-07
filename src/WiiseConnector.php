<?php

namespace Glasswalllab\WiiseConnector;

class WiiseConnector
{
    public function getjobs()
    {
        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tennantId')."Production/ODataV4/Company(".config('wiiseConnector.companyName')."/Job_List?\$select=No,Description,Bill_to_Customer_No,Status,Person_Responsible,Search_Description,Project_Manager";
        dd($url);
    }
}
