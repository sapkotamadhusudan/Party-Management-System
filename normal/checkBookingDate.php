<?php
	include("../common/config.php");
	$d = $_GET['date'];
	$sql = "SELECT * FROM booking where booked_date = '$d'";
	$dataFromDatabase = $connect->query($sql);
	$count = $dataFromDatabase->num_rows;
	echo $count;
?>