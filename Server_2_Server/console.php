<?php
	// define('LOG_FILE', 'upload.log');
	define('DEBUG_LOG_FILE', 'debug.log');

	
	function varDumpToString ($var)
	{
	    ob_start();
	    var_dump($var);
	    return ob_get_clean();
	}
	function db_time_now()
	{
		return date("Y-m-d H:i:s");
	}
	function user_console($string)
	{
		// var_dump($_SERVER);
		// if(($_SERVER['PHP_SELF'] == '/School/User_2_Server/upload.php'))
		// {
		// 	$log_file = LOG_FILE;
		// }
		// else
		// {
			$log_file = DEBUG_LOG_FILE;
		// }
		
		$temp = "<" . db_time_now() . "> " . $string . "\n";
		//echo $temp;
		$log = fopen($log_file, 'a');
		fwrite($log, $temp);
		fclose($log);
		
	}
	// user_console("Starting log...");
//	echo "Starting log...";
?>