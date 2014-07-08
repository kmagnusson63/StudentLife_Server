<?php
	
	/**
				
				Unofficial links pull from database for app

	**/

	require('connect.php');

	$title_sql = "SELECT * FROM official_links";

	$title_result = $db->query($title_sql);

	;
	// echo "Rows: " . count($rows);
	$i = 0;
	echo "{";
	while ($title_rows = $title_result->fetch_assoc()) {
		# code...
	//var_dump($title_rows);
		if($i > 0)
		{
			echo ", ";
		}
		echo '"' . $title_rows['official_link_title'] . '": [';

			$link_sql = "SELECT official_url_link FROM official_urls WHERE official_url_foreign = " . $title_rows['official_link_id'];
	//echo $link_sql;
			$link_result = $db->query($link_sql);
			$k = 0;
			while($link_rows = $link_result->fetch_assoc())
			{
				if($k > 0)
				{
					echo ", ";
				}
				echo '{"link":"' . $link_rows['official_url_link'] . '"}';
				$k++;
			}		
		echo ']';
		$i++;
	}
	echo "}";
?>