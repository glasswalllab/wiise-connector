<?php

namespace glasswalllab\wiiseconnector\TokenStore;

use glasswalllab\wiiseconnector\Models\Token;
use Carbon\Carbon;

class TokenCache {

  public function storeTokens($accessToken) {
   
    $token = Token::updateOrCreate(['provider' => config('wiiseConnector.provider')],
        [
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => Carbon::createFromTimestamp($accessToken->getExpires())->toDateTimeString(),
        ]);
    $token->save();
  }

  public function clearTokens($provider) {
    $tokens = Token::where('provider',$provider)->get();
    foreach($tokens as $token)
    {
        Token::destroy($token->id);
    }
  }

  public function getAccessToken($provider) {

    $token = Token::firstWhere('provider',$provider);
    
    // Check if tokens exist
    if (empty($token)) {
      return '';
    }
    
    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if (strtotime($token->tokenExpires) <= $now) {
        // Token is expired (or very close to it)
        // so let's refresh
  
        // Initialize the OAuth client
        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
          'clientId'                => config('wiiseconnector.appId'),
          'clientSecret'            => config('wiiseconnector.appSecret'),
          'redirectUri'             => config('wiiseconnector.redirectUri'),
          'urlAuthorize'            => config('wiiseconnector.authority').config('wiiseconnector.tenantId').config('wiiseconnector.authoriseEndpoint'),
          'urlAccessToken'          => config('wiiseconnector.authority').config('wiiseconnector.tenantId').config('wiiseconnector.tokenEndpoint'),
          'urlResourceOwnerDetails' => config('wiiseconnector.resource'),
          'scopes'                  => config('wiiseconnector.scopes'),
        ]);
  
        try {
        $newToken = $oauthClient->getAccessToken('refresh_token', [
            'refresh_token' => $token->refreshToken
        ]);

        // Store the new values
        $this->updateTokens($token->id,$newToken);

        return $newToken->getToken();

        }
        catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            return $e;
        }
    }
  
    // Token is still valid, just return it
    return $token->accessToken;
  }

  public function updateTokens($id,$accessToken) {

    Token::where('id',$id)
        ->update([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => Carbon::createFromTimestamp($accessToken->getExpires())->toDateTimeString(),
            
        ]);
  }
}