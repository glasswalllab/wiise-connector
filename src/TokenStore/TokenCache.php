<?php

namespace glasswalllab\wiiseconnector\TokenStore;
use glasswalllab\wiiseconnector\Models\Token;

class TokenCache {

  public function storeTokens($accessToken) {

    $token = new Token;
    $token->provider = 'wiise';
    $token->accessToken = $accessToken->getToken();
    $token->refreshToken = $accessToken->getRefreshToken();
    $token->tokenExpires = $accessToken->getExpires();
    $token->save();
  }

  public function clearTokens(Token $accessToken) {

    Token::destroy($accessToken->id);
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
    if ($token->tokenExpires <= $now) {
      // Token is expired (or very close to it)
      // so let's refresh
  
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => config('wiiseConnector.appId'),
        'clientSecret'            => config('wiiseConnector.appSecret'),
        'redirectUri'             => config('wiiseConnector.redirectUri'),
        'urlAuthorize'            => config('wiiseConnector.authority').config('wiiseConnector.tennantId').config('wiiseConnector.authoriseEndpoint')."?resource=".config('wiiseConnector.resource'),
        'urlAccessToken'          => config('wiiseConnector.authority').config('wiiseConnector.tennantId').config('wiiseConnector.tokenEndpoint')."?resource=".config('wiiseConnector.resource'),
        'urlResourceOwnerDetails' => '',
      ]);
  
      try {
        $newToken = $oauthClient->getAccessToken('refresh_token', [
          'refresh_token' => $token->refreshToken
        ]);
  
        // Store the new values
        $this->updateTokens($newToken);
  
        return $newToken->getToken();
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }
  
    // Token is still valid, just return it
    return $token;
  }

  public function updateTokens($accessToken) {
    Token::where('id',$accessToken->id)
        ->update([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires()
            
        ]);
  }
}