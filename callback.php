<?php
session_start();

const OAUTH2_TOKEN_URL = "https://accounts.google.com/o/oauth2/token";
const OAUTH2_TOKEN_INFO_BASE_URL = "https://oauth2.googleapis.com/tokeninfo";

$clientId = "YOUR_CLIENT_ID";
$clientSecret = "YOUR_CLIENT_SECRET";  // the client secret of my web application on Google
$redirectUri = 'http://localhost:8888/php-google-oauth2-demo/callback.php';


// Should be redirected back from Google with `code` in the GET param
if (!isset($_GET['code']))
{
    exit("No authorization code");
}
// Check given state against previously stored one to mitigate CSRF attack
else if (empty($_GET['state']) || !isset($_SESSION['oauth2_state']) || ($_GET['state'] !== $_SESSION['oauth2_state']))
{
    echo "Invalid state<br>";
    echo "state in GET parameter = " . var_export($_GET['state']);
    echo "state stored in session = " . var_export($_SESSION['oauth2_state']);


    if (isset($_SESSION['oauth2_state']))
        unset($_SESSION['oauth2_state']);
    exit();
}
else
{
    try
    {
        echo "--------------------- URL GET parameters: ---------------------<br>";
        $code = $_GET['code'];
        $scope = $_GET['scope'];
        $state = $_GET['state'];
        echo "code = $code<br>";
        echo "scope = $scope<br>";
        echo "state = $state<br>";

        ############################ Do token exchange ############################
        echo "--------------------- Do token exchange: ---------------------<br>";

        $header = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $postData = [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri
        ];

        $curl = curl_init(OAUTH2_TOKEN_URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));

        $responseData = curl_exec($curl);
        if (empty($responseData))
            echo "<br> Empty!!! <br>";
        $responseData = json_decode($responseData);

        $curlData = curl_getinfo($curl);
        curl_close($curl);
        echo ("Response status code: " . $curlData['http_code']);
        echo ("<br>");

        echo '$responseData:<br><textarea rows="50" cols="40" style="width: 75%; height: 50%;">';
        var_export($responseData);
        echo '</textarea><br><br>';

        $accessToken = $responseData->access_token;
        $expiresIn = $responseData->expires_in;
        $refreshToken = $responseData->refresh_token;
        $scope = $responseData->scope;
        $tokenType = $responseData->token_type;
        $idToken = $responseData->id_token;
        echo "accessToken = $accessToken<br><br>";
        echo "expiresIn = $expiresIn<br><br>";
        echo "refreshToken = $refreshToken<br><br>";
        echo "scope = $scope<br><br>";
        echo "tokenType = $tokenType<br><br>";
        echo "idToken = $idToken<br><br>";

        ############################ Decode idToken ############################
        echo "--------------------- Decode idToken: ---------------------<br>";

        $tokenInfoUrl = OAUTH2_TOKEN_INFO_BASE_URL . "?" . http_build_query(array('id_token' => $idToken));
        $curl = curl_init($tokenInfoUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($curl);
        $responseData = json_decode($responseData);

        $curlData = curl_getinfo($curl);
        curl_close($curl);
        echo ("Response status code: " . $curlData['http_code']);
        echo ("<br>");

        echo '$responseData (decoded idToken) :<br><textarea rows="50" cols="40" style="width: 75%; height: 35%;">';
        var_export($responseData);
        echo '</textarea><br>';


        ############################ Refresh the tokens ############################
        echo "--------------------- Refresh the tokens: ---------------------<br>";

        $header = ['Content-Type: application/x-www-form-urlencoded'];
        $postData = [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        $curl = curl_init(OAUTH2_TOKEN_URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));


        $responseData = curl_exec($curl);
        $responseData = json_decode($responseData);
        if (empty($responseData))
            echo "<br> Empty!!! <br>";

        $curlData = curl_getinfo($curl);
        curl_close($curl);
        echo ("Response status code: " . $curlData['http_code']);
        echo ("<br>");

        echo '$responseData:<br><textarea rows="50" cols="40" style="width: 75%; height: 50%;">';
        var_export($responseData);
        echo '</textarea><br>';

        $accessToken = $responseData->access_token;
        echo "accessToken = $accessToken<br>";

    }

    catch(Exception $exception)
    {
        // Failed to get the access token or user details.
        exit($exception->getMessage());
    }
}
