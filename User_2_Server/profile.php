<?php
	/**

				Script for communication between Student Life App
				and web server.


	**/

	require('connect.php');
	// require('functions.php');

	// Load settings from database
	// require('settings.php');

	// var_dump($_GET);

	if(isset($_GET['id']))
	{
		if(isset($_GET['screen_name']))
		{
			$sql = "SELECT user_id FROM users WHERE user_screen_name = " . $_GET['screen_name'];
			$result = $db->query($sql);

			// var_dump($result);
			if($result)
			{
				echo "Sorry. Screen name taken.";
			}
			else
			{
				echo "Screen name available.";
			}
		}
	}
?>