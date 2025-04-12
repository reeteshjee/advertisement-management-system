<?php
require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once('functions.php');


$conn = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);


$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v12.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    echo 'No access token received';
    exit;
}

// Logged in
$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$userId = $tokenMetadata->getField('user_id');

// Get user details
try {
    $response = $fb->get('/me?fields=id,name,email,picture.width(800).height(800)', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

$data['social_id'] = $social_id = $user->getId();
$data['name'] = $name = $user->getName();
$data['email'] = $email = $user->getEmail();
$data['photo'] = $photo = $user->getPicture()->getUrl();
$data['type'] = $type = 'facebook';


$sql = "select * from users where type='facebook' and social_id='$social_id'";
$result = mysqli_query($conn,$sql);
$result = mysqli_fetch_assoc($result);
if(!$result){
    $created_at = date('Y-m-d H:i:s');
    $sqll = "insert into users(fullname,email,social_id,profile_picture,created_at,type) values('$name','$email','$social_id','$photo','$created_at','$type')";
    mysqli_query($conn,$sqll);
    
    $sql = "select * from users where type='google' and email='$email'";
    $result = mysqli_query($conn,$sql);
    $result = mysqli_fetch_assoc($result);
}else{
    $sqll = "update users set profile_picture='$photo' where type='$type' and social_id='$social_id'";
    mysqli_query($conn,$sqll);
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
