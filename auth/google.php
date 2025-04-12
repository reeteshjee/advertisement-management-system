<?php
require_once('../db.php');
require_once 'vendor/autoload.php';
require_once('functions.php');

$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_CALLBACK_URL);
//$client->addScope('https://www.googleapis.com/auth/userinfo.profile');

$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get user info
    $oauth2 = new Google\Service\Oauth2($client);
    $userData = $oauth2->userinfo->get();
    /*if($userData->getName()=='Ritesh Ghimire'){
        echo "<pre>";
        var_dump($userData);die;
    }*/

    $data['social_id'] = $social_id = $userData->getId();
    $data['name'] = $name = $userData->getName();
    $data['email'] = $email = $userData->getEmail();
    $data['photo'] = $photo = $userData->getPicture();
    $data['photo'] = $photo = str_replace("=s96-c","=s400-c",$photo);
    $data['type'] = $type = 'google';

    $sql = "select * from users where type='google' and email='$email'";
    $result = mysqli_query($conn,$sql);
    $result = mysqli_fetch_assoc($result);
    if(!$result){
        $created_at = date('Y-m-d H:i:s');
        $sqll = "insert into users(fullname,email,social_id,profile_picture,created_at,type) values('$name','$email','$social_id','$photo','$created_at','$type')";
        mysqli_query($conn,$sqll);
        
        $sql = "select * from users where type='google' and email='$email'";
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
    
    // You can use $userData to store user details in your database or do other actions

    // Redirect to the home page or any desired page
    
} else {
    //$authUrl = $client->createAuthUrl();
    //echo '<a href="' . $authUrl . '">Login with Google</a>';
}

header('Location: '.BASE_URL);
exit();