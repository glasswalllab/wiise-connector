<?php

namespace Glasswalllab\WiiseConnector;

use glasswalllab\wiiseconnector\TokenStore\TokenCache;
use Illuminate\Http\Request;

class WiiseConnector
{
    public function getjobs()
    {
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken('wiise');

        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tennantId')."/Production/ODataV4/Company('".config('wiiseConnector.companyName')."')/Job_List?\$select=No,Description,Bill_to_Customer_No,Status,Person_Responsible,Search_Description,Project_Manager";
        $options = [];

        $provider = new \League\OAuth2\Client\Provider\GenericProvider();

        try {
            $request = $provider->getAuthenticatedRequest(
            'GET',
            $url,
            $accessToken,
            $options
            );
          //parse response
          $response = $provider->getResponse($request);
          $result = $contents = json_decode($response->getBody()->getContents());
          dd($result);
          } catch (Exception $ex) {
            dd($ex);
        }
    }
}
