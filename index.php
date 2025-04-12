<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once('db.php');
if(isLoggedIn()){
    header('location:dashboard');
    exit;
}

require_once 'auth/vendor/autoload.php';

// Google OAuth Setup
$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_CALLBACK_URL);
$client->addScope('email');
$client->addScope('profile');
$googleAuthUrl = $client->createAuthUrl();

// Twitter OAuth Setup
$twitteroauth = new Abraham\TwitterOAuth\TwitterOAuth(TWITTER_API_KEY, TWITTER_SECRET_KEY);
$request_token = $twitteroauth->oauth(
    'oauth/request_token',
    array('oauth_callback' => TWITTER_CALLBACK_URL)
);
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$twitterAuthURL = $twitteroauth->url(
    'oauth/authenticate',
    array('oauth_token' => $request_token['oauth_token'])
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            padding: 50px 40px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .login-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 15px;
        }
        .login-header p {
            color: #666;
            font-size: 16px;
            margin-bottom: 0;
        }
        .social-login .btn {
            margin: 12px 0;
            padding: 12px 20px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .social-login .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .social-login .btn i {
            margin-right: 12px;
            font-size: 20px;
        }
        .btn-google {
            background-color: #DB4437;
            color: #fff;
            border: 2px solid #ddd;
        }
        .btn-google:hover {
            background-color: #DB4437;
            color: #fff;
            border: 2px solid #ddd;
        }
        .btn-twitter {
            background-color: #1DA1F2;
            color: white;
            border: none;
        }
        .btn-twitter:hover {
            background-color: #1DA1F2;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h2>Welcomee</h2>
                <p>Choose your preferred login method</p>
            </div>
            
            <div class="social-login">
                <a href="<?php echo $googleAuthUrl;?>" class="btn btn-google w-100">
                    <i class="fab fa-google"></i> Continue with Google
                </a>
                <a href="<?php echo $twitterAuthURL;?>" class="btn btn-twitter w-100">
                    <i class="fab fa-twitter"></i> Continue with Twitter
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>