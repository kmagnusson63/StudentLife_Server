<?php
	define('EVENT_RETRIEVE','SELECT * FROM events ORDER BY event_created_at DESC');
	//define('SOCIAL_FEED','SELECT * FROM posts ORDER BY post_created_at DESC');
	
	
	function db_time()
	{
		return date("Y-m-d H:i:s",strtotime ("-10 day"));
	}
	function retrieve($type,$db)
	{

		if($type == "social")
		{
			$sql = "SELECT * FROM posts WHERE post_created_at > '" . db_time() . "' ORDER BY post_created_at DESC";
		}
		else
		// if($type == "events")
		{
			$sql = EVENT_RETRIEVE;
		}
		$result = $db->query($sql);

		$json_array = array();

		while($row = $result->fetch_assoc())
		{
			if($type == "social")
			{
//var_dump($row);echo "<br>";
				//$row['post_content'] = htmlspecialchars_decode($row['post_content']);
//echo $row['post_content'] . "<BR>";
//var_dump($row);echo "<br>";
				$json_array[] = $row;
			}
			else
			{
				// get user avatar and add to json
				$sql = "SELECT user_admin, user_avatar, user_screen_name FROM users WHERE user_id = " . $row['event_user_id'];
				$user_result = $db->query($sql);
				$user_row = $user_result->fetch_assoc();
				$new_user_row = '{"event_user_admin":"' . $user_row['user_admin'] . '", "event_user_screen_name":"'.$user_row['user_screen_name'].'", "event_user_avatar":"' . $user_row['user_avatar'] . '"}';
			//	var_dump(json_decode($new_user_row));
			//	echo json_encode($user_row);echo "<br>";
			//	echo json_encode($row);echo "<br>";
				//var_dump(json_decode($new_user_row,true));
//var_dump($row); 
				$combined = array_merge($row, json_decode($new_user_row,true));
				//echo "<br>";
				$json_array[] = $combined;
				//var_dump($combined);
				//echo "<br>";
			}
		}
		//print_r(json_encode($json_array));
		echo json_encode($json_array);
	}

	function jobs_convert_date_time($date_time)
	{

		// "Mon, 10 Mar 2014 20:54:39 +0000"
		$blog_date_format = "D, d M Y H:i:s e";
		// Database timestamp format = "2014-02-06 08:57:59"
		$db_timestamp_format = "Y-m-d H:i:s";
		// convert blog time to database timestamp
		$temp = date_create_from_format($blog_date_format, $date_time);

		$new_timestamp = date_format($temp, $db_timestamp_format);
		return $new_timestamp;
	}
?>