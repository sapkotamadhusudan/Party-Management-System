<?php
$u_id = $_GET['id'];
session_start();
session_destroy();
header("location:../index.php");
?>