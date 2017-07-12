<?php
	include("../common/config.php");
	$id = $_GET['book_id'];
	$sql = "DELETE FROM booking WHERE id=$id";
	$connect->query($sql);
	header("location:BookedParty.php");
?>