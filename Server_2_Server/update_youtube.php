<?php
	/*
		https://www.facebook.com/dialog/oauth?client_id=189868871224263&redirect_uri=http://www.gristlebone.com/School/Server_2_Server/update_facebook.php&scope=manage_pages,user_status
		
		https://graph.facebook.com/oauth/access_token?client_id=189868871224263&redirect_uri=http://www.gristlebone.com/School/Server_2_Server/update_facebook.php&client_secret=e291ebcd8ad81c721bab74d50a0ae324&code=AQAS1spIa2WYheGRFMB_JaFThnNeaKd9n_WTIs5W5F_dvIpCEkuYAHNLrUwliUv77CDgq1pKUuk9xyxgqUWIDf0Y9q9_hNDElYoWmE0pnDNB61WXE4ue5YMDEzZplnocIwebnFnoP_EQZZILiBgg-EWilSPKm_AY_7MQlB0Eqkz1P8OHADLAgiHtHRxlkwMOQqcdVCRfIl_RiVJHxs_6HRp49F_uHoVC4yj2Rk0NORPCGL91DOerK8Gdp_KwnQ5jq9mCySbLnhuC2c621_tEI_hZ85mTJaQRbTO20E2q2IAQZjnfvYyXlv0bQqkeS9jxwEs
	
		access_token=CAACsr0qfi8cBAJYyjfXRWz7ZBjy3eVWIIZCfPBm4VUICZBESEz1Qi27pmJ7IGCV3QUrMMI6wt3T3xdZAD1BYWvwNVtGCLtNZBvrtbWwSvSgqZAqSLNq84cyxjYcVgose0GoIzRmDRwOnlwTwpCkhK87llb9ITaaZAjgJYqpZA73NZBAjoKPkUREAl&expires=5183859
	
		https://graph.facebook.com/me?fields=statuses&access_token=CAACsr0qfi8cBAJYyjfXRWz7ZBjy3eVWIIZCfPBm4VUICZBESEz1Qi27pmJ7IGCV3QUrMMI6wt3T3xdZAD1BYWvwNVtGCLtNZBvrtbWwSvSgqZAqSLNq84cyxjYcVgose0GoIzRmDRwOnlwTwpCkhK87llb9ITaaZAjgJYqpZA73NZBAjoKPkUREAl
	*/
	require('console.php');
		define('DB_HOST','68.178.143.5');
	define('DB_USER','rrcproject');
	define('DB_PASS','UserPass1!');
	define('DB_NAME','rrcproject');
	// define('DB_HOST','192.168.8.10');
	// define('DB_USER','root');
	// define('DB_PASS','studentlife');
	// define('DB_NAME','StudentLife');
	define('APP_ID','467750821306-4rnd36agqol4unejhopi2pchnhnee0ke.apps.googleusercontent.com');
	define('APP_SECRET','ueN_r4svvlUI6XWokWy4rjCA');
	define('SERVER_KEY','AIzaSyAZcvcUZmHgo9oh7kr7r8BIFntt7AYI7b0');
user_console('Test: ');

	// $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
$redirect_uri = "http://www.gristlebone.com/School/Server_2_Server/update_youtube.php";
user_console($redirect_uri);
	// Create a MySQLi resource object called $db.
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 

	// If an error occurs we can look here for more info:
	$connection_error = mysqli_connect_errno();
	$connection_error_message = mysqli_connect_error();

	// if(isset($_GET['code']))
	// {
	// 	// Save code to database
	// 	$sql = "UPDATE tokens SET token_code = '" . $_GET['code'] . "' WHERE token_app_id = '" . APP_ID . "'";
	// 	$result = $db->query($sql);

	// 	if(!$result)
	// 	{
	// 		echo 'ERROR getting code from instagram';
	// 		echo $sql;
	// 		die();
	// 	}
	// 	else
	// 	{
			// Generate access token
			$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=status&id=RedRiverCollege&key=' . SERVER_KEY;
			// $param = 'client_id=' . APP_ID . '&client_secret='. APP_SECRET .'&grant_type=authorization_code&redirect_uri=' . $redirect_uri . '&code=' . $_GET['code'];
user_console($url);
			
			// Send app id and code to facebook to return access token
			$curl_http = curl_init($url);
			
			curl_setopt($curl_http, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($curl_http);
			curl_close($curl_http);
user_console("REsponse:");
user_console(varDumpToString($response));
var_dump($response);

			// Split response into json
			// $json = $response;
// 			$json = json_decode($response, true);
// 			$access_token = $json['access_token'];
// user_console(varDumpToString($access_token));

	// 		// $expiry = explode('=',$json[1]);
			
	// 		$sql = "UPDATE tokens SET token_access_token = '" . $access_token . "' WHERE token_app_id = '" . APP_ID . "'";
	// 		$result = $db->query($sql);

	// 		if(!$result)
	// 		{
	// 			echo 'ERROR getting access token from facebook';
	// 			echo $sql;
	// 			die();
	// 		}
	// 		else
	// 		{
	// 			// Generate access token
	// 			echo "Access Token generated and stored";
	// 		}

			
	// 	}
	// }
	// else
	// {
	// 	header('Location: https://api.instagram.com/oauth/authorize/?client_id='.APP_ID.'&redirect_uri=' . $redirect_uri . '&response_type=code');
	// }
?>