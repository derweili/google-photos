<?php

require('./vendor/autoload.php');

// Start the session
session_start();

$provider = new League\OAuth2\Client\Provider\Google([
    'clientId'     => '79928623652-dttuc3nsqvm17koiuphob6lfa071cti0.apps.googleusercontent.com',
    'clientSecret' => 'n_L8UNcYaQMirsIdDti5rx9q',
    'redirectUri'  => 'http://werbeagenten-google-oauth-test.de',
    'hostedDomain' => 'http://werbeagenten-google-oauth-test.de',
]);

if (!empty($_GET['error'])) {

    // Got an error, probably user denied access
    exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

} elseif (empty($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl(array(
      "scope" => array(
        'email',
        'openid',
        'profile',
        'https://picasaweb.google.com/data/'
      )
    ));
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;

} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    // State is invalid, possible CSRF attack in progress
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    echo 'token: ' . $token . '  ';

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the owner details
        $ownerDetails = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $ownerDetails->getFirstName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Something went wrong: ' . $e->getMessage());

    }


echo 'test<br>';
echo '<pre>';
try {

        // We got an access token, let's now get the owner details
        echo 'request: <br>';
        $request = $provider->getAuthenticatedRequest('GET', 'https://picasaweb.google.com/data/feed/api/user/default', $token);
        // $response = $provider->getParsedResponse($request);
        echo 'getParsedResponse: <br>';
        // $response = $provider->getParsedResponse($request);
        $response = $provider->getResponse($request);
        $content = (string) $response->getBody();

        $albums = new SimpleXMLElement($content);

        // echo $movies->movie[0]->plot;

        // $contentType = $provider->getContentType($response);
        // echo 'Content Type: <br>';
        var_dump($albums->entry[0]);


        echo '$content: <br>';
        // var_dump($albums);
        echo '<ul>';
        foreach ($albums->entry as $album) {
          # code...
          echo '<li>' . $album->title . '</li>';
        }
        echo '</ul>';
        // printf('Albums: ', $response);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Something went wrong: ' . $e->getMessage());

    }

    //
    // // Use this to interact with an API on the users behalf
    // echo $token->getToken();
    //
    // // Use this to get a new access token if the old one expires
    // echo $token->getRefreshToken();
    //
    // // Number of seconds until the access token will expire, and need refreshing
    // echo $token->getExpires();
}
