<?php
include("config.php");

$username = $_GET['us'];

	$sql = "SELECT * FROM user WHERE username='$username'";
	$result = $connect->query($sql);

	$count = $result->num_rows;
	echo $count;
?>