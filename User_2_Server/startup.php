<?php
	/**

			Startup script - creates user in database with default settings and returns id
 
	**/
	require('connect.php');
	require('functions.php');
	require('console.php');

	function getUser($db, $id)
	{
		$sql = "SELECT * FROM users WHERE user_id = '" . $id . "'";
	user_console('IN getUSer with ' . $sql);
		$result = $db->query($sql);
		$data = $result->fetch_assoc();
	user_console("Returning: " . json_encode($data));
		return $data;
	}

	user_console(varDumpToString($_POST));

	// Check for user_id, if true send user data, false create new user
	if(isset($_POST['user_id']))
	{
		if($_POST['user_id'] == "new")
		{
			// Create new user in database with default values
			$sql = "INSERT INTO users (user_screen_name, user_avatar, user_event_pin_limit) VALUES ('Anon','user-default.png',3)";
			$result = $db->query($sql);

			// Get new user id and info from DB
			$sql = "SELECT * FROM users WHERE user_id = (SELECT LAST_INSERT_ID())";
			$result = $db->query($sql);
			$data = $result->fetch_assoc();

			// echo data to console.log
			user_console("Creating new user: " . json_encode($data));

			echo json_encode($data);
		}
		elseif($_POST['action'] == 'update')
		{
			$sql = "UPDATE users SET user_screen_name='" . $_POST['screen_name'] . "' WHERE user_id = '" . $_POST['user_id'] . "'";
		user_console("Updating: " . $sql);	
			$result = $db->query($sql);
			if($result)
			{
				user_console("Successfully updated");
				$data = getUser($db, $_POST['user_id']);
				user_console($data);
				echo json_encode($data);
			}
			else
			{
				user_console("ERROR updating");
				echo "ERROR";
			}
		}
		else
		{
			// Get user info from DB
			$data = getUser($db, $_POST['user_id']);

			// Send user info to log file
			user_console("Logging in: " . json_encode($data));

			echo json_encode($data);
		}
	}



?>