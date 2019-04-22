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
                                To logout follow the below link:
                            </p>
                        </header>

                        <div class="post-description">
                            <p>
                                Logout URL (note this is the standard page and so will return to the community login page, not this demo site): <a href="<?php echo getenv('SALESFORCE_LOGOUT_URL') ?>">Logout</a>
                            </p>

                        </div>
                    </section>
                </div>




            </div>
        </div>
    </div>




</body>
</html>

