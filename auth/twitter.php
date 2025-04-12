<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../db.php';
require "vendor/autoload.php";
require_once('functions.php');

use Abraham\TwitterOAuth\TwitterOAuth;
$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);


$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

$twitteroauth = new TwitterOAuth(TWITTER_API_KEY, TWITTER_SECRET_KEY, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$accessToken = $twitteroauth->oauth('oauth/access_token', ['oauth_verifier' => $oauth_verifier, 'oauth_token' => $_SESSION['oauth_token']]);


$twitteroauth = new TwitterOAuth(TWITTER_API_KEY, TWITTER_SECRET_KEY, $accessToken['oauth_token'],$accessToken['oauth_token_secret']);

$user_info = $twitteroauth->get('account/verify_credentials',['include_email'=>true]);



$data['social_id'] = $social_id = $user_info->id;
$data['name'] = $name = $user_info->name;
$data['email'] = $email = $user_info->email;
$data['photo'] = $photo = str_replace('_normal','_400x400',$user_info->profile_image_url_https);
$data['type'] = $type = 'twitter';


$sql = "select * from users where type='twitter' and social_id='$social_id'";
$result = mysqli_query($conn,$sql);
$result = mysqli_fetch_assoc($result);
if(!$result){
    $created_at = date('Y-m-d H:i:s');
    $sqll = "insert into users(fullname,email,social_id,profile_picture,created_at,type) values('$name','$email','$social_id','$photo','$created_at','$type')";
    mysqli_query($conn,$sqll);
    
    $sql = "select * from users where type='twitter' and email='$email'";
    $result = mysqli_query($conn,$sql);
    $result = mysqli_fetch_assoc($result);
}

$_SESSION['admin'] = $result['id'];
$_SESSION['social_id'] = $social_id;
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['photo'] = $photo;
$_SESSION['type'] = $type;
$_SESSION['logged_in'] = true;
//createImage($photo,$type,$social_id);

header('Location: '.BASE_URL);
exit;