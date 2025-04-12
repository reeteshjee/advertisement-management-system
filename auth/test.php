<?php
require 'config.php';
require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

// Replace these with your own Twitter app credentials
$apiKey = "70jwM5LpB6JtW4qWZVftoRQSC";
$apiSecret = "MFApnLlyObu3HpLscRJOzF69EfZhfQ5rR5lcOiwp7c4MOnrBlu";

// Initialize TwitterOAuth with your app credentials
$twitteroauth = new TwitterOAuth($apiKey, $apiSecret);

// Retrieve the stored request token and secret from your storage (e.g., session)
session_start();
$requestToken = $_SESSION['oauth_token'];
$requestTokenSecret = $_SESSION['oauth_token_secret'];

// Retrieve the verifier from the URL (sent by Twitter after user authorization)
$verifier = $_GET['oauth_verifier'];

// Exchange the request token and verifier for an access token
$accessToken = $twitteroauth->oauth('oauth/access_token', ['oauth_verifier' => $verifier, 'oauth_token' => $requestToken]);

// Store the obtained access token and secret for later use
$accessToken = $accessToken['oauth_token'];
$accessTokenSecret = $accessToken['oauth_token_secret'];

// Use the obtained access token and secret to make an API call to get user information
$twitteroauth = new TwitterOAuth($apiKey, $apiSecret, $accessToken, $accessTokenSecret);
$user_info = $twitteroauth->get('account/verify_credentials');

// Output the obtained user information (for demonstration purposes)
echo "User ID: " . $user_info['id'] . "<br>";
echo "Screen Name: " . $user_info['screen_name'] . "<br>";
echo "Name: " . $user_info['name'] . "<br>";

// Perform any additional actions or redirections as needed for your application

// Clear the stored request token and secret from the session (optional)
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

?>
