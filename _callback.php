<?php

// Lib to format the token response for the screen
require('nicejson.php');

// Get the code from the HTTP GET parameter invoking this page
if (isset($_GET['code'])) {
    $code = $_GET['code'];
}

//Set up to post to the token URL
$url = getenv('SALESFORCE_TOKEN_URL');

//Set data fields for POST
$fields = [
    'code' => $code,
    'grant_type' => getenv('SALESFORCE_GRANT_TYPE'),
    'client_id' => getenv('SALESFORCE_CLIENT_ID'),
    'client_secret' => getenv('SALESFORCE_CLIENT_SECRET'),
    'redirect_uri' => getenv('SALESFORCE_CALLBACK_URL')
];

//URL-ify the data for the POST
$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Execute post
$tokenresult = curl_exec($ch);

//Close connection
curl_close($ch);

// Normally you would now validate the Access Token against the keys provided by the Salesforce community.
// You could also call any REST endpoint on the Salesforce community using the returned access token.

// Check if default experience ID has been changed, generate URL entry if it has
// The experience ID needs to be appended to the standard authorize URL as a virtual directory, e.g. https://authorize.url/expid_value
if (getenv('SALESFORCE_EXPID') == "EXPID_HERE") {
    $expid = "";
} else {
    $expid = "/".getenv('SALESFORCE_EXPID');
}

/**
 * Build authorize URL from environment parameters set in the Heroku instance
 * It is created in the format:
 *  <Authorize Endpoint>?client_id=<Client ID>&redirect_uri=<callback URL>&response_type=code&scope=<Scopes>&state=<Requested State>
 **/

$authURL = getenv('SALESFORCE_AUTHORIZE_URL') . $expid . "?client_id=" . getenv('SALESFORCE_CLIENT_ID') . "&redirect_uri=" . getenv('SALESFORCE_CALLBACK_URL') . "&response_type=code&scope=" . getenv('SALESFORCE_SCOPE') . "&state=" . getenv('SALESFORCE_STATE');

// URLencode the authURL to use it as a parameter
$authURLencoded = urlencode($authURL);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Simple callback page to illustrate exchange of authorisation code for access and ID token">
    <title>Salesforce Identity Returned Values</title>

    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-" crossorigin="anonymous">

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
    <!--<![endif]-->


    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/blog-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/blog.css">
    <!--<![endif]-->
</head>
<body>


    <div id="layout" class="pure-g">
        <div class="content pure-u-1 pure-u-md-3-4">
            <div>
                <div class="posts">
                    <section class="post">
                        <header class="post-header">

                            <h2 class="post-title">Callback Details</h2>

                            <p class="post-meta">
                                Returned information passed to the callback end point (<?php echo getenv('SALESFORCE_CALLBACK_URL') ?>) is below.
                            </p>
                        </header>

                        <div class="post-description">
                            <p>Auth Code Returned: <?php echo $code; ?></p>
                        </div>
                    </section>

                    <section class="post">
                        <header class="post-header">

                            <h2 class="post-title">ID Token Details</h2>

                            <p class="post-meta">
                                The ID token returned from calling the token endpoint with the provided code is is below.
                            </p>
                        </header>

                        <div class="post-description">
                            <p>
                                Code response details:
                            </p>
                            <p><pre><?php echo json_format($tokenresult); ?></pre></p>
                        </div>
                    </section>


                    <section class="post">
                        <header class="post-header">

                            <h2 class="post-title">Logout</h2>

                            <p class="post-meta">
                                To change your password follow the below link:
                            </p>
                        </header>

                        <div class="post-description">
                            <p>
                                Changing password also revokes all your tokens (to ensure locking out previous users) therefore if you need to then query the user you will need to run login again - the user will not see this as they are logged in.
                                This call invokes change password then uses the authorise endpoint as the return url to do this in one: <a href="<?php echo getenv('SALESFORCE_CHANGE_PASSWORD') . $authURLencoded ?>">Change Password</a>
                            </p>

                        </div>
                    </section>

                    <section class="post">
                        <header class="post-header">

                            <h2 class="post-title">Logout</h2>

                            <p class="post-meta">
                                To logout follow the below link:
                            </p>
                        </header>

                        <div class="post-description">
                            <p>
                                Logout URL: <a href="<?php echo getenv('SALESFORCE_LOGOUT_URL') ?>">Logout</a>
                            </p>

                        </div>
                    </section>
                </div>




            </div>
        </div>
    </div>




</body>
</html>

