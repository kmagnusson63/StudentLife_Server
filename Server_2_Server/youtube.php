<?php
	/**

			Pull YouTube feed

	**/

require('connect.php');
require('functions.php');

	$url = "http://gdata.youtube.com/feeds/api/users/RedRiverCollege/uploads?v=2&alt=json";

	$curl_http = curl_init($url);
		curl_setopt($curl_http, CURLOPT_RETURNTRANSFER, 1);
		$feed = curl_exec($curl_http);
	curl_close($curl_http);
	
	$json = json_decode($feed,true);
	update_database($db, "YouTube", $json['feed']['entry']);
?>