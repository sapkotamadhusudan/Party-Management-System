<?php

	include("../common/config.php");

	$userId = $_GET['userId'];
	$sql = "SELECT * FROM childrens c, users u WHERE c.parent_id = u.user_id and u.user_id = $userId";
	$dataFromDatabase = $connect->query($sql);
	$count = $dataFromDatabase->num_rows;
	echo $count;
?>