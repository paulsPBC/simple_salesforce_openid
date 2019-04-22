# simple-oidc-demo
Simple OIDC Demo

Pre-requisites
--------------
You'll need an org that has a Community setup and active. Ideally you should have following the [Salesforce External Identity Implementation Guide](https://developer.salesforce.com/docs/atlas.en-us.externalidentityImplGuide.meta/externalidentityImplGuide/external_identity_intro.htm) to set up this org.

Steps to deploy
---------------
<ol>
<li>Deploy this app to Heroku: (https://heroku.com/deploy?template=https://github.com/paulsPBC/simple_salesforce_openid)
<li>Set the environment variables based on your Community. Most will be settings from your connected app, but some can be set to defaults unless specific testing is needed, namely:
<ul>
<li>SALESFORCE_GRANT_TYPE: authorization_code
<li>SALESFORCE_SCOPE: openid email profile
<li>SALESFORCE_STATE: Can be any state value you wish returned from the authorize call
<li>SALESFORCE_EXPID: Set this if you have implemented dynamic branding else leave it at the default of EXPID_HERE
</ul>
<li>Add the heroku environment as a callback URL to your connected app (https://yourdomain.herokuapp.com/_callback.php)</ol>

Other components used
---------------
<ul><li>CSS from https://purecss.io/
<li>JSON formatting from https://github.com/GerHobbelt/nicejson-php</ul>