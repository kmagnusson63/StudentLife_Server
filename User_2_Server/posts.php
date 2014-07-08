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


	require('connect.php');
	require('functions.php');
	//define('EVENT_RETRIEVE','SELECT * FROM events ORDER BY event_created_at DESC');

	if((isset($_GET['start'])) || ($_GET["lat"] == null))
	{
		//echo "here";
	}
	else
	{
		// build sql query string
		$sql = "INSERT INTO events (event_content, event_lat, event_long, event_user_id) VALUES ('" . htmlspecialchars($_GET["content"]) . "', '" . $_GET["lat"] . "', '" . $_GET["long"] . "', '" . $_GET["user_id"] . "')";
		$results = $db->query($sql);
	}
	

	retrieve('events',$db);
?>
