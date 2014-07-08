<?php
	require('connect.php');
	require('console.php');
	require('functions.php');

	define('UPLOAD_DIR', '/home/content/01/10894901/html/School/User_2_Server/images/');

user_console("Files: \n" . varDumpToString($_FILES));
	user_console("POST: \n" . varDumpToString($_POST));
//	user_console(varDumpToString($_SERVER));
	if(isset($_POST['img_name']) && isset($_POST['base64data']))
	{
		$test_data = substr($_POST['base64data'],strpos($_POST['base64data'], ",")+1);
user_console(strlen($test_data));
		$temp_image_name = $_POST['user_id'] . '_' . substr(str_shuffle(MD5(microtime())), 0, 5) . substr($_POST['img_name'],-4);
		$written = file_put_contents(UPLOAD_DIR . $temp_image_name, base64_decode($test_data));
user_console("Wrote " . $_POST['img_name'] . ", size " . $written ." bytes");
	
		// Save info to database
		$sql = "UPDATE users SET user_screen_name='" . $_POST['screen_name'] . "', user_avatar='" . $temp_image_name . "' WHERE user_id='" . $_POST['user_id'] . "'";
user_console($sql);
		$result = $db->query($sql);
		if($result)
		{
			$sql = "SELECT * FROM users WHERE user_id= '" . $_POST['user_id'] . "'";
			$result = $db->query($sql);
			$data = $result->fetch_assoc();
			echo json_encode($data);
user_console(json_encode($data));
user_console("User info updated successfully");
		}
		else
		{
			echo $result;
user_console("Error updating user database");
		}
	}
	elseif ($_FILES) {
		user_console(varDumpToString($_FILES));
// var_dump($_POST);
// var_dump($_FILES); echo "<br>";

// echo UPLOAD_DIR;
		// foreach($_FILES['files'] as $file)
	 //    {

	    	    	user_console("start test");
	    	    	user_console("Filename: " . $_FILES['files']['name'][0]);
					user_console("Temp Filename: " . $_FILES['files']['tmp_name'][0]);
					user_console("Done test");
//var_dump($img);

					$new_filename = $_POST['screen_name']."_".$_POST['user_id']."_".$_FILES['name'];
		    	    $file_name = UPLOAD_DIR . $_FILES['files']['name'][0];
// echo $file;
		    	    $data = file_get_contents($_FILES['files']['tmp_name'][0]);
		    	    $success = file_put_contents($file_name, $data);

		    	    user_console( $success ? $file_name." received" : 'Unable to save the file.');
		// }
	}
	else
	{
		echo "no file sent";
	}

?> 