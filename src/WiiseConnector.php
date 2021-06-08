<?php

namespace Glasswalllab\WiiseConnector;

use glasswalllab\wiiseconnector\TokenStore\TokenCache;
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
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken('wiise');

        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tennantId')."/Production/ODataV4/Company('".config('wiiseConnector.companyName')."')".$endpoint;

        $options['headers']['content-type'] = 'application/json';
        $options['body'] = $body; //json encoded value

        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => config('wiiseConnector.appId'),
            'clientSecret'            => config('wiiseConnector.appSecret'),
            'redirectUri'             => config('wiiseConnector.redirectUri'),
            'urlAuthorize'            => config('wiiseConnector.authority').config('wiiseConnector.tennantId').config('wiiseConnector.authoriseEndpoint')."?resource=".config('wiiseConnector.resource'),
            'urlAccessToken'          => config('wiiseConnector.authority').config('wiiseConnector.tennantId').config('wiiseConnector.tokenEndpoint')."?resource=".config('wiiseConnector.resource'),
            'urlResourceOwnerDetails' => '',
            'scopes'                  => config('wiiseConnector.scopes'),
          ]);

        try
        {
            $request = $oauthClient->getAuthenticatedRequest(
                $method,
                $url,
                $accessToken,
                $options,
            );

            //parse response
            $response = $oauthClient->getResponse($request);
            $result = $contents = json_decode($response->getBody()->getContents());

            return($result);

        } catch (Exception $ex) {
            return($ex);
        }
    }
}
