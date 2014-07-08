<?php

	require_once('../User_2_Server/console.php');

	define('DB_HOST','68.178.143.5');
	define('DB_USER','rrcproject');
	define('DB_PASS','UserPass1!');
	define('DB_NAME','rrcproject');        

	// Create a MySQLi resource object called $db.
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 

	// If an error occurs we can look here for more info:
	$connection_error = mysqli_connect_errno();
	$connection_error_message = mysqli_connect_error();
	
	$find = array('â€œ', 'â€™', 'â€¦', 'â€”', 'â€“', 'â€˜', 'Ã©', 'Â', 'â€¢', 'Ëœ', 'â€'); // en dash
	$replace = array('“', '’', '…', '—', '–', '‘', 'é', '', '•', '˜', '”');
//$content = str_replace($find, $replace, $content);

	function blog_convert_date_time($date_time)
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
	function twitter_convert_date_time($date_time)
	{
		// Twitter created_at variable retrieves as "Wed Feb 05 22:48:00 +0000 2014"
		$twitter_date_format = "D M d H:i:s e Y";

		// Database timestamp format = "2014-02-06 08:57:59"
		$db_timestamp_format = "Y-m-d H:i:s";

		// convert twitter time to database timestamp
		$temp = date_create_from_format($twitter_date_format, $date_time);

		$new_timestamp = date_format($temp, $db_timestamp_format);
		return $new_timestamp;
	}

	function instagram_convert_date_time($date_time)
	{
		// Database timestamp format = "2014-02-06 08:57:59"
		$db_timestamp_format = "Y-m-d H:i:s";

		return date($db_timestamp_format, $date_time);
	}
	
	function facebook_convert_date_time($date_time)
	{
		return date("Y-m-d H:i:s",strtotime($date_time));
	}	
	function strip_u($string)
	{	
		return preg_replace('/\\\\x([0-9a-f]{2})/e', 'chr(hexdec(\'$1\'))', $string);
	}

	/*
			Parses JSON object and stores into POSTS table in database

			$db 		database connection object
			$post_type 	String of social media type
			$data 		JSON object of media feed

	*/
	function update_database($db, $post_type, $data)
	{
		$link = $web_link = "NULL";
		
		$insert_string = "INSERT INTO `posts` (`post_created_at`, `post_type`, `post_content`, `post_image_link`, `post_web_link`, `post_site_id`) VALUES ";
		
		for($i=0; $i < count($data) ; $i++)
		{ 
			if($post_type == "Twitter")
			{
				$created_at = twitter_convert_date_time($data[$i]['created_at']);
				$content = $data[$i]['text'];
				$id = $data[$i]['id_str'];
			}
			elseif($post_type == "Facebook")
			{
 				$created_at = facebook_convert_date_time($data[$i]['updated_time']);
 				$content = $data[$i]['message'];
 				$id = $data[$i]['id'];
 				$link = "NULL";
			}
			elseif($post_type == "Instagram")
			{
 				$created_at = instagram_convert_date_time($data[$i]['created_time']);
 				if(isset($data[$i]['caption']['text']))
 				{
 					$content = $data[$i]['caption']['text'];
 				}
 				else
 				{
 					$content = 'NULL';
 				}
 				$web_link = $data[$i]['link'];
 				$id = $data[$i]['id'];
 				$link = $data[$i]['images']['standard_resolution']['url'];
			}
			elseif($post_type == "YouTube")
			{
				$created_at = facebook_convert_date_time($data[$i]['published']['$t']);
				$content = $data[$i]['title']['$t'];
				$link = $data[$i]['media$group']['media$thumbnail'][0]['url'];
				$web_link = $data[$i]['media$group']['media$content'][0]['url'];

				$id = $data[$i]['id']['$t'];
			}
			elseif($post_type == "Blog")
			{
				$created_at = $data[$i]['created_at'];
				$content = $data[$i]['title'];
				$id = $data[$i]['id'];
 				$link = "NULL";
			}
			$insert_row = '("' . $created_at . '", ';
			$insert_row = $insert_row . '"' . $post_type . '", ';
			$insert_row = $insert_row . '"' . strip_u( str_replace("\n", " ",$content)) . '", ';
			$insert_row = $insert_row . '"' . $link . '", ';
			$insert_row = $insert_row . '"' . $web_link . '", ';
			$insert_row = $insert_row . '"' . $id . '");';

			$insert_sql = $insert_string . $insert_row;
echo $insert_sql."<br>";
			$result = $db->query($insert_sql);
			var_dump($result);

		}
		echo "<br>";
	}


	function retrieve_last_post($type,$db)
	{
		if($type == "twitter")
		{
			$sql = "SELECT `post_site_id` FROM `posts` ORDER BY `post_site_id` DESC LIMIT 1";
			$result = $db->query($sql);
		}
		return $result;
	}
	function since_date($db)
	{
		$since =  '&since_id=';
		$result = retrieve_last_post("twitter",$db);
		if($result->num_rows > 0)
		{
			$last_post_id = $result->fetch_assoc();
			$since = $since . $last_post_id['post_site_id'];
		}
		else
		{
			$since = $since . "1";
		}

		return $since;
	}
?>