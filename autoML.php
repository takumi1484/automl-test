<?php
require 'vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('ignore/client_secret_285087211861-am2l2v9sckhg821jb2cq5m7mm5ehg0mn.apps.googleusercontent.com.json');
$client->addScope(Google_Service_Prediction::PREDICTION);
$client->setRedirectUri('http://localhost:8080/callback.php');
$client->setAccessType('offline');        // offline access
$client->setIncludeGrantedScopes(true);   // incremental auth

//$client -> authenticate($_GET [ 'code' ]);

$access_token = $client -> getAccessToken();

//print_r($access_token);

//$client -> authenticate($_GET [ 'code' ]);
//print_r($client->getClientId());
$client->setRedirectUri('http://localhost:8080/callback.php' );
$auth_url = $client -> createAuthUrl();
print_r($auth_url);



//$client = new Google_Client();
//$client->useApplicationDefaultCredentials();
//$access_token = $client -> getAccessToken();
//print_r($access_token);