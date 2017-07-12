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
	    	$sql="UPDATE booking SET booked_date='$date', no_of_child_attend=$no_of_children_attending,cost_type='$costType' total_cost=$totalCost WHERE id=$book_id";
	    	$connect->query($sql);

	    	$to = "rajindaChildrenParty4u@gmail.com";
	    	$subject = "For updating Booking information";
	    	$message = $userFname." wants to update booking for ". $party_name . ". /n/r
	    						Total child attending : - " .$no_of_children_attending . "/r/n
	    						Total cost : - " . $totalCost."/r/n
	    						Booking date : - " . $requested_booking_date;
	    	$header = "From: " . $userMail;
	    	mail($to, $subject, $message, $header);
	    	header("location:home.php");
 ?>