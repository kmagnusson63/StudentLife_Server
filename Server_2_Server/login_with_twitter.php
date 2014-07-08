<?php
/*
 * login_with_twitter.php
 *
 * @(#) $Id: login_with_twitter.php,v 1.6 2013/07/31 11:48:04 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	
	require('http.php');
	require('oauth_client.php');
	require('functions.php');

	// get last post id
	$since = since_date($db);

	$client = new oauth_client_class;
	$client->debug = 1;
	$client->debug_http = 1;
	$client->server = 'Twitter';
	$client->redirect_uri = 'https://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_twitter.php';
//	$client->redirect_uri = 'oob';

	$client->client_id = 'NokCeqdq5Pg1W3tR1B8QupBla'; $application_line = __LINE__;
	$client->client_secret = 'sdM6WXF1Ygq0QL2w7UZ3XjnWmos7csGQFNk8kTeMdrOfFoRcPl';

	// $client->access_token = '151996500-0sWkhTlc2kVXaTpR20DDVUUxZfvqcSzAoiDaM3gS';
	// $client->access_token_secret = ' KjEZRA86Q9GczdANpvvEcjatnSGi0IMXzUh6lofVLD5WF';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to Consumer key and client_secret with Consumer secret. '.
			'The Callback URL must be '.$client->redirect_uri.' If you want to post to '.
			'the user timeline, make sure the application you create has write permissions');

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				/* 'https://api.twitter.com/1.1/account/verify_credentials.json' */
				$success = $client->CallAPI(
					
					'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=rrc&count=200', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	//echo "<br>Twitter<br>";

	if($success)
	{
// 		echo "Last tweet #: " . $since . "<br>";  // echo $last_post_id['post_site_id'] . "<br />";
// //		echo json_encode($user) . "<br />";
 		//print_r(json_encode($user));
		if(count($user) == 0)
		{
			echo "Nothing New<br>";
		}
		else
		{
			echo "Updating database<br>";
			update_database($db,"Twitter", json_encode($user));
		}

	}
	else
	{
		echo "OAuth client error" . "<br />";
		echo "echoError: " . HtmlSpecialChars($client->error);


	}

?>
