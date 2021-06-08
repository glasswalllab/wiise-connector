<?php

namespace glasswalllab\wiiseconnector\Jobs;

use glasswalllab\wiiseconnector\TokenStore\TokenCache;
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

        $url = config('wiiseConnector.baseUrl').config('wiiseConnector.tennantId')."/Production/ODataV4/Company('".config('wiiseConnector.companyName')."')".$this->endpoint;

        $options['headers']['content-type'] = 'application/json';
        $options['body'] = $this->body; //json encoded value
        
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
                $this->method,
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