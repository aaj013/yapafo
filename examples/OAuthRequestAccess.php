<?php
/**
 * An OAuth example for Yapafo OSM_OAuth.
 */

//
// Loading Yapafo library
//

$OSM_Api_filename = __DIR__.'/../lib/OSM/Api.php' ;
if( !file_exists($OSM_Api_filename))
{
	echo 'ERROR, Could not find OSM/Api.php ("'.$OSM_Api_filename.'")'."\n";
}
require_once($OSM_Api_filename);

//
// Let's go !
//

// Phase 2
//$consumerKey = isset($_REQUEST['consumerKey']) ? $_REQUEST['consumerKey'] : 'K0Fc6En9GulO9nBrJ6Bz7ltHcZRL9vD3kqDMaX8V' ;
//$consumerSecret = isset($_REQUEST['consumerSecret']) ? $_REQUEST['consumerSecret'] : 'HRv7OOGCA2bKYcfw1Jlbg8nCXodHOCSAAHhY1XU4' ;
$consumerKey = isset($_REQUEST['consumerKey']) ? $_REQUEST['consumerKey'] : null ;
$consumerSecret = isset($_REQUEST['consumerSecret']) ? $_REQUEST['consumerSecret'] : null ;

// Phase 3
$authUrl = null ;
$authReqToken = isset($_REQUEST['authReqToken']) ? $_REQUEST['authReqToken'] : null ; ;
$authReqTokenSecret = isset($_REQUEST['authReqTokenSecret']) ? $_REQUEST['authReqTokenSecret'] : null ; ;
$authAccessToken = null ;
$authAccessTokenSecret = null ;

if( !empty($consumerKey) && !empty($consumerSecret) )
{
	$oauth = new OSM_Auth_OAuth($consumerKey, $consumerSecret);

	if( empty($authReqToken) && empty($authReqTokenSecret) )
	{
		$authCredentials = $oauth->requestAuthorizationUrl();
		$authUrl = $authCredentials['url'];
		$authReqToken = $authCredentials['token'];
		$authReqTokenSecret = $authCredentials['tokenSecret'];
	}
	else
	{
		$oauth->setToken($authReqToken, $authReqTokenSecret);
		$authCredentials = $oauth->requestAccessToken();
		$authAccessToken = $authCredentials['token'];
		$authAccessTokenSecret = $authCredentials['tokenSecret'];
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <!--
  An OAuth example for Yapafo OSM_OAuth.
  -->
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" lang="en"></meta>
    <title>OSM OAuth request access</title>
		<style type="text/css">
			ol li {
				border-top-style: dashed;
				border-top-color: #aaa ;
				list-style-type: upper-roman;
				padding: 1.5em;
				margin-right: 1.5em;
			}
		</style>
  </head>
  <body>
		<h1>OSM OAuth request access</h1>
		
		<ol>
			<li>Create an application credentials aka "Consumer Key" and "Consumer Secret" at <a href="http://osm.org/user/[YOUR USERNAME]/oauth_clients">http://osm.org/user/[YOUR USERNAME]/oauth_clients</a>.</li>
			<li>Fill them here:
				<form method="POST">
					<label for="consumerKey">Consumer Key:</label>
					<input name="consumerKey" type="text" value="<?php echo $consumerKey; ?>" size="64" />
					<br/>
					<label for="consumerSecret">Consumer Secret:</label>
					<input name="consumerSecret" type="text" value="<?php echo $consumerSecret; ?>" size="64" />
					<br/>
					<input type="submit" value="Ask authorization url"/>
					<?php
					if( !empty($authUrl) ){
						?>
					<p>
					Let's go to <a href="<?php echo $authUrl ?>"><?php echo $authUrl ?></a> to Accept the application authorization request.<br/>
					Then come back here to process the next step.
					</p>
						<?php
					}
					?>
				</form>
			</li>
			<li>
				<form method="POST">
					The Access Token will be used by the application to use your account for her self.
					<br/>
					<input type="hidden" name="consumerKey" value="<?php echo $consumerKey; ?>" size="64" />
					<input type="hidden" name="consumerSecret" value="<?php echo $consumerSecret; ?>" size="64" />
					<input type="hidden" name="authReqToken" value="<?php echo $authReqToken; ?>"/>
					<input type="hidden" name="authReqTokenSecret" value="<?php echo $authReqTokenSecret; ?>"/>
					<input type="submit" value="Get access token"/>
				</form>
				<?php
				if( !empty($authAccessToken) ){
					?>
				<p>
					<label for="authAccessToken">Access Token:</label>
					<input name="authAccessToken" type="text" readonly="readonly" value="<?php echo $authAccessToken; ?>" size="64" />				
					<br/>
					<label for="authAccessTokenSecret">Access Token Secret:</label>
					<input name="authAccessTokenSecret" type="text" readonly="readonly" value="<?php echo $authAccessTokenSecret; ?>" size="64" />				
				</p>
					<?php
				}
				?>
			</li>
		</ol>

	</body>
</html>
