<?php
/*
 * login_with_facebook.php
 *
 * @(#) $Id: login_with_facebook.php,v 1.3 2013/07/31 11:48:04 mlemos Exp $
 *
 */
	require('http.php');
	require('oauth_client.php');
require('functions.php');
	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'Facebook';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_facebook.php';

	$client->client_id = '189868871224263'; $application_line = __LINE__;
	$client->client_secret = 'e291ebcd8ad81c721bab74d50a0ae324';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Facebook Apps page https://developers.facebook.com/apps , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to App ID/API Key and client_secret with App Secret');

	/* API permissions
	 */
	$client->scope = 'email, user_status';

	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://graph.facebook.com/5853623502?fields=statuses.fields(id,message,updated_time)', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	echo "<br>Facebook<br>";
	if($client->exit)
		exit;

	if($success)
	{

		//echo json_encode($user);
		//echo "getting time from facebook"."<br />";
		$data = json_encode($user);
		$datab = json_decode($data);
		$statuses = $datab->statuses->data;
		// echo count($statuses); echo "<br />";
		// echo $statuses[0]->updated_time;echo "<br />";
		// echo facebook_convert_date_time($statuses[0]->updated_time);
		if(count($data) == 0)
		{
			echo "Nothing New<br>";
		}
		else
		{
			echo "Updating database<br>";
			//update_database($db,"Facebook", json_encode($statuses));
		}
	}
	else
	{
		echo "OAuth client error" . "<br />";
		echo "echoError: " . HtmlSpecialChars($client->error);


	}

?>