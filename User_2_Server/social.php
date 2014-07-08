<?php
	/*
		Social Feed retrieval

	*/

	require('connect.php');
	require('functions.php');

	$sql = "SELECT * FROM posts WHERE post_created_at > '" . db_time() . "' ORDER BY post_created_at DESC";
	$result = $db->query($sql);
	while($row = $result->fetch_assoc())
	{
//var_dump($row['post_content']);	echo "<br>";
		//$row['post_content'] = htmlspecialchars_decode($row['post_content']);
//var_dump($row['post_content']);	echo "<br>";
		$json_array[] = $row;
	}
	echo json_encode($json_array);
?>