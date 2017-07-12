<?php
define ('DB_HOST_NAME',"localhost");
define ('DB_username',"root");
define ('DB_password',"");
define ('DB_name',"childrenparty");

$connect= new mysqli(DB_HOST_NAME,DB_username,DB_password,DB_name);

if($connect -> connect_error)
{
	echo"connectionfailed:".$connect->connect_error;	
}
?>


