<?php
$url = '';
$callbackUrl = $url . "oauth_admin.php";
$temporaryCredentialsRequestUrl = $url . "oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
$adminAuthorizationUrl = $url . 'console_gestione/oauth_authorize';
$accessTokenRequestUrl = $url . 'oauth/token';
$apiUrl = $url . 'api/rest';
$consumerKey = '';
$consumerSecret = '';
$token = 'token';
$secret = 'token_secret';

try {
$oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
$oauthClient->setToken($token, $secret);
$resourceUrl = "$apiUrl/products";
$oauthClient->fetch($resourceUrl, array(), 'GET', array('Content-Type' => 'application/json', 'Accept' => 'application/json'));
$productsList = json_decode($oauthClient->getLastResponse());
echo '<pre>';
    print_r($productsList);
}
catch(Exception $e) {
    echo '<pre>';
    print_r($e);
}
?>