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
    <title>AdFlow - Next-Gen Ad Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #0f172a;
            color: #e2e8f0;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            background: #1e293b;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #38bdf8;
        }

        .nav-links a {
            margin-left: 2rem;
            text-decoration: none;
            color: #94a3b8;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #38bdf8;
        }

        .hero {
            padding: 6rem 5%;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 3.5rem;
            color: #f8fafc;
            margin-bottom: 1.5rem;
        }

        .subtitle {
            font-size: 1.25rem;
            color: #94a3b8;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .login-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 4rem;
        }

        .login-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .google-login {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
        }

        .twitter-login {
            background: #1DA1F2;
            color: white;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: #1e293b;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2rem;
            color: #38bdf8;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.25rem;
            color: #f8fafc;
            margin-bottom: 0.75rem;
        }

        .feature-text {
            color: #94a3b8;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 2rem;
            background: #1e293b;
            margin-top: 4rem;
        }
        .text-decoration-none{
            text-decoration: none;
        }
        .text-center{
            text-align: center;
        }
        .d-flex{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }

            .login-buttons {
                flex-direction: column;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="d-flex">
        <div class="logo">AdFlow</div>
    </nav>

    <section class="hero">
        <h1>Ad Management, Simplified</h1>
        <p class="subtitle">
            Effortlessly manage your ads, track performance, and optimize revenue in real-time. AdFlow is the future of ad management.
        </p>
        <div class="login-buttons">
            <a href="<?php echo $googleAuthUrl;?>" class="text-decoration-none login-button google-login">
                <i class="fab fa-google"></i>
                Sign in with Google
            </a>
            <a href="<?php echo $twitterAuthURL;?>" class="text-decoration-none login-button twitter-login">
                <i class="fab fa-twitter"></i>
                Sign in with Twitter
            </a>
        </div>
    </section>

    <section class="features">
        <div class="feature-card">
            <i class="fas fa-bolt feature-icon"></i>
            <h3 class="feature-title">Instant Ad Updates</h3>
            <p class="feature-text">Modify your ads instantly without changing your website's code.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-chart-line feature-icon"></i>
            <h3 class="feature-title">Real-time Analytics</h3>
            <p class="feature-text">Track impressions, clicks, and revenue with powerful insights.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-shield-alt feature-icon"></i>
            <h3 class="feature-title">Secure & Reliable</h3>
            <p class="feature-text">Enterprise-grade security ensures your data stays protected.</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 AdFlow. All rights reserved.</p>
    </footer>
</body>
</html>
