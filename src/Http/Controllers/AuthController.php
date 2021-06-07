<?php

namespace glasswalllab\wiiseconnector\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function signin()
  {
    // Initialize the OAuth client
    $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => config('wiise.appId'),
      'clientSecret'            => config('config.appSecret'),
      'redirectUri'             => config('config.redirectUri'),
      'urlAuthorize'            => config('config.authority')."/".config('config.tennantId')."/".config('config.authoriseEndpoint')."?resource=".config('config.resource'),
      'urlAccessToken'          => config('config.authority')."/".config('config.tennantId')."/".config('config.tokenEndpoint')."?resource=".config('config.resource'),
      'urlResourceOwnerDetails' => config('config.authority'),
    ]);

    $authUrl = $oauthClient->getAuthorizationUrl();

dd(config('wiise.appId'));

    // Save client state so we can validate in callback
    session(['oauthState' => $oauthClient->getState()]);

    // Redirect to AAD signin page
    return redirect()->away($authUrl);
  }

  public function callback(Request $request)
  {
    // Validate state
    $expectedState = session('oauthState');
    $request->session()->forget('oauthState');
    $providedState = $request->query('state');

    if (!isset($expectedState)) {
      // If there is no expected state in the session,
      // do nothing and redirect to the home page.
      return redirect('/');
    }

    if (!isset($providedState) || $expectedState != $providedState) {
      return redirect('/')
        ->with('error', 'Invalid auth state')
        ->with('errorDetail', 'The provided auth state did not match the expected value');
    }

    // Authorization code should be in the "code" query param
    $authCode = $request->query('code');
    if (isset($authCode)) {
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => config('config.appId'),
        'clientSecret'            => config('config.appSecret'),
        'redirectUri'             => config('config.redirectUri'),
        'urlAuthorize'            => config('config.authority')."/".config('config.tennantId')."/".config('config.authoriseEndpoint')."?resource=".config('config.resource'),
        'urlAccessToken'          => config('config.authority')."/".config('config.tennantId')."/".config('config.tokenEndpoint')."?resource=".config('config.resource'),
        'urlResourceOwnerDetails' => config('config.authority'),
      ]);

      try {
        // Make the token request
        $accessToken = $oauthClient->getAccessToken('authorization_code', [
          'code' => $authCode
        ]);

        // TEMPORARY FOR TESTING!
        return redirect('/')
          ->with('error', 'Access token received')
          ->with('errorDetail', $accessToken->getToken());
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return redirect('/')
          ->with('error', 'Error requesting access token')
          ->with('errorDetail', $e->getMessage());
      }
    }

    return redirect('/')
      ->with('error', $request->query('error'))
      ->with('errorDetail', $request->query('error_description'));
  }
}