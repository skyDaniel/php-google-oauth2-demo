<?php
require __DIR__ . '/vendor/autoload.php';

session_start();
if (isset($_SESSION['oauth2state']))
    unset($_SESSION['oauth2state']);

const OAUTH2_AUTH_BASE_URL = "https://accounts.google.com/o/oauth2/v2/auth";

$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";  // the client secret of my web application on Google
$redirectUri = 'http://localhost:8888/php-google-oauth2-demo/callback.php';
$scope = 'email profile';
$state = bin2hex(random_bytes(16));

$_SESSION['oauth2_state'] = $state; // store the state into session

$queryParams = array(
    'client_id'           => $clientId, // the client id of my web application on Google
    'redirect_uri'        => $redirectUri, // when Google auth succeeds, redirect user back to this url
    'response_type'       => 'code',
    'scope'               => $scope, // the resources that our web application could access on the user's behalf from Google
    'state'               => $state, // to mitigate CSRF attack, will check if the returned state matches when user get redirected back after Google auth is done
    'access_type'         => 'offline' // 'offline': our app can refresh users access token at any time without prompting the user for permission
);

$googleAuthUrl = OAUTH2_AUTH_BASE_URL . "?" . http_build_query($queryParams);
// Example url: https://accounts.google.com/o/oauth2/v2/auth?client_id=290737942855-0bpda68us9t1dqrr546u1ue2a4h38fak.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost%3A8888%2Fphp-google-oauth2%2Fauth.php&response_type=code&scope=email+openid+profile&state=e5ce5d26dee97b0addb062c34bb9cfe9&access_type=offline&flowName=GeneralOAuthFlow
?>



<head>
    <title>Auth Page</title>
    <style>
        #google-auth-link {
            text-decoration: none;
            color: #757575;
        }
        .login-with-google-wrapper{
            margin: 20px;
        }
        .login-with-google-btn {
            transition: background-color .3s, box-shadow .3s;

            padding: 12px 16px 12px 42px;
            border: none;
            border-radius: 3px;
            box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);

            color: #757575;
            font-size: 14px;
            font-weight: 500;

            background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
            background-color: white;
            background-repeat: no-repeat;
            background-position: 12px 11px;
        }
    </style>
</head>
<div class="login-with-google-wrapper">
    <button onclick="location.href='<?=$googleAuthUrl?>'; return false;" class="login-with-google-btn">
        <a href="<?=$googleAuthUrl?>" id="google-auth-link">Continue with Google</a>
    </button>
</div>
