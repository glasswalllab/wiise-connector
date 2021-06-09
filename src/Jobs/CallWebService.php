<?php

namespace glasswalllab\wiiseconnector\Jobs;

use glasswalllab\wiiseconnector\TokenStore\TokenCache;
use glasswalllab\wiiseconnector\Events\ResponseReceived;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CallWebService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $endpoint;
    private $method;
    private $body;
    private $authClient;

    public function __construct($endpoint,$method,$body)
    {
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->body = $body;
    }

    public function handle()
    {
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken('wiise');

        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tenantId')."/Production/ODataV4/Company('".config('wiiseConnector.companyName')."')".$this->endpoint;

        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['If-Match'] = '*';

        $options['body'] = $this->body; //json encoded value
        
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
                $this->method,
                $url,
                $accessToken,
                $options,
            );

            $response = $this->oauthClient->getResponse($request);
            return json_decode($response->getBody()->getContents());
            //event(new ResponseReceived($oauthClient->getResponse($request)));
            
        } catch (Exception $ex) {
            return($ex);
        }
    }
}