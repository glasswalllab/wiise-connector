<?php

namespace Glasswalllab\WiiseConnector;

use glasswalllab\wiiseconnector\Jobs\CallWebService;
use glasswalllab\wiiseconnector\TokenStore\TokenCache;
use Illuminate\Http\Request;

class WiiseConnector 
{
    public function CallWebServiceSync($endpoint,$method,$body)
    {  
        //Could move the below to job - but was having issues with the return
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken(config('Connector.provider'));

        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tenantId')."/Production/ODataV4/Company('".config('wiiseConnector.companyName')."')".$endpoint;

        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['If-Match'] = '*';

        $options['body'] = $body; //json encoded value
        
        $this->oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => config('wiiseConnector.appId'),
            'clientSecret'            => config('wiiseConnector.appSecret'),
            'redirectUri'             => config('wiiseConnector.redirectUri'),
            'urlAuthorize'            => config('wiiseConnector.authority').config('wiiseConnector.tenantId').config('wiiseConnector.authoriseEndpoint'),
            'urlAccessToken'          => config('wiiseConnector.authority').config('wiiseConnector.tenantId').config('wiiseConnector.tokenEndpoint'),
            'urlResourceOwnerDetails' => config('wiiseConnector.resource'),
            'scopes'                  => config('wiiseConnector.scopes'),
        ]);

        try
        {
            $request = $this->oauthClient->getAuthenticatedRequest(
                $method,
                $url,
                $accessToken,
                $options,
            );

            $response = $this->oauthClient->getResponse($request);
            return $response->getBody()->getContents();
            //event(new ResponseReceived($oauthClient->getResponse($request)));
            
        } catch (Exception $ex) {
            return($ex);
        }
    }

    public function CallWebServiceQueue($endpoint,$method,$body)
    {  
        $call = CallWebService::dispatch($endpoint,$method,$body);
    }
}
