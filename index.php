<?php

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

$authURL = getenv('SALESFORCE_AUTHORIZE_URL').$expid."?client_id=".getenv('SALESFORCE_CLIENT_ID')."&redirect_uri=".getenv('SALESFORCE_CALLBACK_URL')."&response_type=code&scope=".getenv('SALESFORCE_SCOPE')."&state=".getenv('SALESFORCE_STATE');
// URLencode the authURL to use it as a parameter
$authURLencoded = urlencode($authURL);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sample OIDC Connect Demo">
    <title>Sample Salesforce OpenID Connect</title>
    
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-" crossorigin="anonymous">
    
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-old-ie-min.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/grids-responsive-min.css">
    <!--<![endif]-->
    
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/layouts/marketing-old-ie.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
            <link rel="stylesheet" href="css/layouts/marketing.css">
        <!--<![endif]-->
</head>
<body>

<div class="splash-container">
    <div class="splash">
        <h1 class="splash-head">Open ID Connect Demo</h1>
        <p class="splash-subhead">
            Login below (for direct registration use the <a href="<?php echo getenv('SALESFORCE_REG_URL') . $authURLencoded ?>" >register</a> link)
        </p>
        <p>
            <a href="<?php echo $authURL; ?>" class="pure-button pure-button-primary">Login</a>
        </p>
    </div>
</div>


</body>
</html>
