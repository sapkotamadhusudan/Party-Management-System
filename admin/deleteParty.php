<?php
	include("../common/config.php");
	
	$id = $_GET['id'];	
	$delete="delete from party where id='$id'";
	$result= $connect->query($delete);
	header("location:../admin.php");
?>