<?php

namespace Glasswalllab\WiiseConnector;

use Illuminate\Http\Request;

class WiiseConnector
{
    public function getjobs()
    {
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken('wiise');

        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tennantId')."/Production/ODataV4/Company('".config('wiiseConnector.companyName')."')/Job_List?\$select=No,Description,Bill_to_Customer_No,Status,Person_Responsible,Search_Description,Project_Manager";
        
        $request = Request::create($url,'GET');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Authorization', 'Bearer '.$accessToken);
        $res = app()->handle($request);
        
        dd($res);
    }
}
