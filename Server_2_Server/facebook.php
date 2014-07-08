<?php
	require('functions.php');

	define('DB_HOST','68.178.143.5');
	define('DB_USER','rrcproject');
	define('DB_PASS','UserPass1!');
	define('DB_NAME','rrcproject');
	define('APP_ID','685674891484274');
		

	// Create a MySQLi resource object called $db.
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 

	// If an error occurs we can look here for more info:
	$connection_error = mysqli_connect_errno();
	$connection_error_message = mysqli_connect_error();

	$sql = "SELECT token_access_token FROM tokens WHERE token_type = 'Facebook'";
	$result = $db->query($sql);

	$row = $result->fetch_assoc();

	$url = "https://graph.facebook.com/5853623502?fields=statuses.fields(id,message,updated_time)&access_token=" . $row['token_access_token'];

	$curl_http = curl_init($url);
		curl_setopt($curl_http, CURLOPT_RETURNTRANSFER, 1);
		$feed = curl_exec($curl_http);
	curl_close($curl_http);

	$data = json_decode($feed, true);
	
	update_database($db,"Facebook",$data['statuses']['data']);

?>