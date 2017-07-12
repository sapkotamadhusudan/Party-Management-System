<?php 
	include("../common/config.php");
    		$party_name = "";
	    	$party_id = $_POST['party_id'] ;
	    	$no_of_children_attending= $_POST['no_of_children_to_book'];
	    	$costType = $_POST['costType'];
	    	$totalCost = $_POST['totalCostOfBooking'];
	    	$requested_booking_date = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
	    	$user_id = $_POST['userId'];
	    	$userFname = $_POST['userName'];
	    	$userMail = $_POST['userMail'];

			$date = date("Y-m-d", strtotime($requested_booking_date));
			
	    	$sql = "INSERT INTO booking VALUES (NULL, '$date', $no_of_children_attending, '$costType', $totalCost, $user_id, $party_id)";
	    	$connect->query($sql);

	    	$to = "rajindaChildrenParty4u@gmail.com";
	    	$subject = "For Booking information";
	    	$message = $userFname." wants to book ". $party_name . ". \n\r
	    						Total child attending : - " .$no_of_children_attending . "\n\r
	    						Total cost : - " . $totalCost."\n\r
	    						Booking date : - " . $requested_booking_date;
	    	$header = "From: " . $userMail;
	    	mail($to, $subject, $message, $header);
	    	header("location:home.php");
 ?>