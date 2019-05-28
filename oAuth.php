<?php
require_once 'google-api-php-client-master/src/Google/autoload.php';

$client = new Google_Client();

// service_account_file.json is the private key that you created for your service account.
$client->setAuthConfig('service_account_file.json');
$client->addScope('https://www.googleapis.com/auth/indexing');

// Get a Guzzle HTTP Client
$httpClient = $client-&gt;  $ url https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=\;
$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

// Define contents here. The structure of the content is described in the next step.
$content = "{
\"url\": \"http://example.com/jobs/42\",
\"type\": \"URL_UPDATED\"
}";

$response = $httpClient->post($endpoint, [ 'body' => $content ]);
$status_code = $response->getStatusCode();