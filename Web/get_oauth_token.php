<?php

namespace PHPMailer\PHPMailer;

require_once __DIR__ . '/../setup.php';

// @see https://github.com/thephpleague/oauth2-google
use League\OAuth2\Client\Provider\Google;

session_start();

$providerName = 'Google';
$_SESSION['provider'] = $providerName;
$clientId = $_ENV['OAUTH_CLIENT_ID'];
$clientSecret = $_ENV['OAUTH_SECRET'];
$redirectUri = $_ENV['OAUTH_REDIRECT_URI'];

$params = [
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri' => $redirectUri,
    'accessType' => 'offline'
];

$provider = new Google($params);
$options = [
    'scope' => [
        'https://mail.google.com/'
    ]
];

if (!isset($_GET['code'])) {
    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    unset($_SESSION['provider']);
    exit('Invalid state');
} else {
    unset($_SESSION['provider']);
    $token = $provider->getAccessToken(
        'authorization_code',
        [
            'code' => $_GET['code']
        ]
    );
    echo 'Refresh Token: ', $token->getRefreshToken();
}
