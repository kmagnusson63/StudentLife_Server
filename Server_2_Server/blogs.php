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


	$content = file_get_contents('http://news.rrc.ca/feed/');
	$xml_content = new SimpleXMLElement($content);
	$blogs = array();
	foreach($xml_content->channel->item as $blog)
    {
    	//echo $blog->title;echo "<br>";
		//echo blog_convert_date_time($blog->pubDate);echo "<br>";
		//echo substr($blog->guid,strpos($blog->guid,"=")+1);echo "<br>";
		$temp_string = '{ "title":"'.$blog->title.'", "created_at":"'.blog_convert_date_time($blog->pubDate).'", "id":"'.substr($blog->guid,strpos($blog->guid,"=")+1).'"}';
		//echo $temp_string;echo "<br>";
		array_push($blogs,json_decode($temp_string,true));
	}
	update_database($db, "Blog", json_encode($blogs));
?>
