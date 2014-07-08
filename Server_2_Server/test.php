<?php
	/**
	 *
	 *		submit events received from users
	 *		
	 *
	 *		Receives variables via a post http request, parses data into
	 *		SQL statement and sends to database.
	 *	 
	 */
	
	require('functions.php');

	// require('connect.php');
	// require('functions.php');
	//define('EVENT_RETRIEVE','SELECT * FROM events ORDER BY event_created_at DESC');

	// phpinfo();

	// $api_key = 'NokCeqdq5Pg1W3tR1B8QupBla';
	// $api_secret = 'sdM6WXF1Ygq0QL2w7UZ3XjnWmos7csGQFNk8kTeMdrOfFoRcPl';
	// $access_token = '151996500-0sWkhTlc2kVXaTpR20DDVUUxZfvqcSzAoiDaM3gS';
	// $access_token_secret = 'KjEZRA86Q9GczdANpvvEcjatnSGi0IMXzUh6lofVLD5WF';

	$api_key = 'yPaqaXaJs3Wm3d1EQp1Yg';
	$api_secret = 'gf1GmUGI0Jx76H9x84Vex2e9i6hCQscCAd3rPslcmnQ';
	$access_token = '9118212-rOenOMH5e5f0VoMUX0PdClyIqRTdqZ1HYOFtusy5W0';
	$access_token_secret = '3vBGuSUDGKTzg2apM1X5upVGzwyv4fn8jfELzGTi4';

	$oauth_hash = '';

	$oauth_hash .= 'oauth_consumer_key=';
	$oauth_hash .= $api_key;
	$oauth_hash .= '&oauth_nonce=' . time() . '&';

	$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';

	$oauth_hash .= 'oauth_timestamp=' . time() . '&';

	$oauth_hash .= 'oauth_token=';
	$oauth_hash .= $access_token;
	$oauth_hash .= '&oauth_version=1.0';

	$base = '';

	$base .= 'GET';

	$base .= '&';

	$base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');

	$base .= '&';

	$base .= rawurlencode($oauth_hash);

	$key = '';

	$key .= rawurlencode($api_secret);

	$key .= '&';

	$key .= rawurlencode($access_token_secret);

	$signature = base64_encode(hash_hmac('sha1', $base, $key, true));

	$signature = rawurlencode($signature);

	$oauth_header = '';

	$oauth_header .= 'oauth_consumer_key="';
	$oauth_header .= $api_key;
	$oauth_header .= '", oauth_nonce="' . time() . '", ';

	$oauth_header .= 'oauth_signature="' . $signature . '", ';

	$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';

	$oauth_header .= 'oauth_timestamp="' . time() . '", ';

	$oauth_header .= 'oauth_token="';
	$oauth_header .= $access_token;
	$oauth_header .= '", oauth_version="1.0", ';

	$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');

	$curl_request = curl_init();

	curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);

	curl_setopt($curl_request, CURLOPT_HEADER, false);

	curl_setopt($curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json');

	curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);

	$json = curl_exec($curl_request);

	curl_close($curl_request);

    //var_dump( ($json));
    update_database($db,"Twitter", json_decode($json, true));
	// echo $results;
?>
