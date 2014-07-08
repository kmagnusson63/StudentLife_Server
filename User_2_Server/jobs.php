<?php


	
	
	require('functions.php');

	$br = "<br />";
	$feed_url = 'https://jobcentral.rrc.ca/rss.ashx';

	ini_set("user_agent","Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
	ini_set("max_execution_time", 0);
	ini_set("memory_limit", "10000M");

	$rss = simplexml_load_file($feed_url);

	$feed = json_decode(json_encode($rss));
	$items = $feed->channel->item;
	$job_json = "";
	$job_json .= '[';
	// $job_json .= '	"jobs":';
	$i = 0;
	foreach ($items as $job)
	{
		$job_json .= '{';
		// get job number
		$job_id = substr($job->guid,strpos($job->guid,"=")+1);
		if(strrpos($job->title," - ",-1))
		{
			$job_title = substr($job->title,0,strrpos($job->title," - ",-1));
			$job_company = substr($job->title,strrpos($job->title," - ",-1)+3);
		}
		else
		{
			$job_title = $job->title;
			$job_company = "";
		}		
		
		$job_json .= '"job_number":"' . $job_id . '",';
		$job_json .= '"job_title":"' . $job_title . '",';
		// $job_json .= '"job_company":"' . $job_company . '",';
		$job_json .= '"job_description":"' . $job_title . ' - ' . $job->description . '",';
		$job_json .= '"job_open_date":"' . jobs_convert_date_time($job->pubDate) . '" }, ';
		$i++;
	}
	$job_json = substr($job_json, 0,-3);
	$job_json .= '	}';
	$job_json .= ']';

	echo $job_json;
	
?>