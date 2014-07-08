<?php
/*
 * login_with_instagram.php
 *
 * @(#) $Id: login_with_instagram.php,v 1.2 2013/07/31 11:48:04 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'Instagram';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_instagram.php';

	$client->client_id = 'bfb2a65e4a8d434fb73953c1140da2e1'; $application_line = __LINE__;
	$client->client_secret = 'ac2e74286ed2477b99e85278de760311';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Instagram Apps page http://instagram.com/developer/register/ , '.
			'create an application, and in the line '.$application_line.
			' set the client_id to client id key and client_secret with client secret');

	/* API permissions
	 */
	$client->scope = 'basic';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.instagram.com/v1/users/self/', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
				if(!$success)
				{
					$client->ResetAccessToken();
				}
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{

		echo '<h1>', HtmlSpecialChars($user->data->full_name), 
			' you have logged in successfully with Instagram!</h1>';
		echo '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';

	}
	else
	{
		echo "Error: ".HtmlSpecialChars($client->error); 
	}

?>