<head>
    <title>Token Revocation</title>
</head>
<body>
<h1>Token Revocation</h1>
<p>Input the access token to revoke it</p>
<form>
    <div>
        <label for="token">Access token: </label>
<!--        <input type="text" id="token" name="accessToken" size="60" required/>-->
        <div>
            <textarea id="token" name="accessToken" rows="5" cols="100"></textarea>
        </div>
        <div>
            <button type="submit">Revoke token<br />(Disconnect Google account from the app)</button>
        </div>
    </div>
</form>
</body>


<?php
    const OAUTH2_REVOKE_TOKEN_URL = "https://oauth2.googleapis.com/revoke";

    ############################ Token Revocation ############################
    if ( !isset($_GET['accessToken']) || $_GET['accessToken'] === '')
        exit();

    $accessToken = trim($_GET['accessToken']);

    echo "--------------------- Token Revocation: ---------------------<br>";
    $header = ['Content-Type: application/x-www-form-urlencoded'];
    $postData = [
        'token' => $accessToken,
    ];

    $curl = curl_init(OAUTH2_REVOKE_TOKEN_URL);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));

    $responseData = curl_exec($curl);

    $curlData = curl_getinfo($curl);
    curl_close($curl);
    echo ("Response status code: " . $curlData['http_code']);
    echo ("<br>");
?>




