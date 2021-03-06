<?php
// include our OAuth2 Server object
require_once __DIR__.'/server.php';
$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// validate the authorize request
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die;
}
// display an authorization form
if (empty($_POST)) {
  exit('
    <html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <style>
    body {font-family: Verdana,Arial,sans-serif;}
     </style>
    </head>
    <body>
    <h1 align="center">MillieSoft OAuth2 server for Vera</h1>
    <p>This site is an dummy OAuth2 server for accessing your home automation device from an Amazon Echo device. No personal details are stored.<p>
<form method="post">
 <p align="center">
    <button type="submit" name="authorized" value="yes">Authorize</button>&nbsp
    <button type="submit" name="authorized" value="no">Cancel</button>
</p>
</form>
</body>
</html>
    ');
}

// print the authorization code if the user has authorized your client
$is_authorized = ($_POST['authorized'] === 'yes');
$server->handleAuthorizeRequest($request, $response, $is_authorized);
if ($is_authorized) {
  // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
//  exit("SUCCESS! Authorization Code: $code");
}
$response->send();
?>
