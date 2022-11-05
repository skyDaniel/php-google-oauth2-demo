<?php
$clientId = "YOUR_CLIENT_ID";
$redirectUri = 'http://localhost:8888/php-google-oauth2-demo/callback.php';
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://accounts.google.com/gsi/client" onload="initClient()" async defer></script>
</head>
<body>
<script>
    function onSuccess(response) {
        console.log(`Well Done! Auth code = ${response['code']}`);
    }

    function onFailure(response) {
        console.log(`Error: ${response['message']}`);
    }

    var client;
    function initClient() {
        // Use GIS Popup UX
        client = google.accounts.oauth2.initCodeClient({
            client_id: <?=$clientId?>,
            scope: 'email profile',
            ux_mode: 'popup',

            callback: onSuccess,
            // callback: (response) => {
            //     console.log(`Well Done! Auth code = ${response['code']}`);
            // },

            error_callback: onFailure
            // error_callback: (response) => {
            //     cconsole.log(`Error: ${response['message']}`);
            // },

        });
        // console.log("Finish initClient()");
    }
    function getAuthCode() {
        // Request authorization code and obtain user consent
        client.requestCode();
    }
</script>
<button onclick="getAuthCode();">Continue with Google</button>
</body>
</html>
