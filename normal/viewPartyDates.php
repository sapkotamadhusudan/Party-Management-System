<?php
	include("../common/config.php");
	session_start();// Starting Session
	if(!isset($_SESSION['id']) &&  !isset($_SESSION['type'])){
		header('location: ../index.php'); // Redirecting To Home Page
	}
	if ($_SESSION['type'] != "normal") {
		header('location: ../index.php'); // Redirecting To Home Page
 	}


	////////////////// user details /////////////////////
	$id = $_SESSION['id'];
	$sql = "SELECT * FROM users WHERE user_id='$id'";
	$result = $connect->query($sql);
	$data = $result->fetch_array(); 

/////////////////// date /////////////////////////////////
	function checkLeapYear($year)//year in integer
	{
		if ($year % 4 ==  0) { return true;}
		else { return false;}
	}

	
	function getDaysOfMonth($year, $month) //year and month in integer
	{
		if (checkLeapYear($year)) { $feb = 29;}
		else { $feb = 28;}
		$noOfDaysInMonth = array(31, $feb, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		return $noOfDaysInMonth[$month];   //returns specific day 
	}

	function getMonthName($month) {
		// create array to hold name of each month
		$months = array("January", "February", "March", "April", "May", "June", "July", "August", 
						"September", "October", "November", "December");

		return $months[$month];
	}

	function checkBookedDate($day, $month, $year, $connect)
	{
		$sql = "SELECT * FROM booking";
		$dataFromDatabase = $connect->query($sql);
		while ($data= $dataFromDatabase->fetch_assoc()) {
			$timestamp = strtotime($data['booked_date']);
			$bDay =  date("d", $timestamp);
			$bMonth =  date("m", $timestamp);
			$bYear =  date("Y", $timestamp);
			if ($bDay == $day && $bMonth == $month && $bYear == $year) { return true; }
		}

		return false;
	}

	// Set the date
	$enablePrev = true; // to prevent users to view previous month booking details
	$date = strtotime(date("Y-m-d"));
	$day = date('d', $date);
	$month = date('m', $date);
	$year = date('Y', $date);

	if (isset($_POST['pre'])) {
		$timestamp = strtotime($_POST['month-year']);
		$month =  date("m", $timestamp);
		$year =  date("Y", $timestamp);
		$month -=1;
		// checks if button should enable or not
		if ($month <= date('m', $date) && $year <= date('Y', $date)) { $enablePrev = true; }
		else{ $enablePrev = false; }
		if ($month < 1) { $month = 12; $year -=1; }
	}
	else if (isset($_POST['next'])) {
		$enablePrev = false;
		$timestamp = strtotime($_POST['month-year']);
		$month =  date("m", $timestamp);
		$year =  date("Y", $timestamp);
		$month +=1;
		if ($month > 12) { $month = 1; $year +=1; }
	}
		
	$index = $month - 1; // it should decrease coz in array there is index 0-11
	$monthName = getMonthName($index);
	
	$firstDay = mktime(0,0,0,$month, 1, $year);
	$daysInMonth = getDaysOfMonth($year, $index);

	if ($index <= 1) {
		$tempYear = $year-1;
		$tempMonthIndex = 11;
		$lastMonthDays = getDaysOfMonth($tempYear, $tempMonthIndex);
	}else{
		$lastMonthDays = getDaysOfMonth($year, $index-1);
	}
	
	$weekDays = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	$blank = date('w', strtotime("{$year}-{$month}-01"));  //provides month no. of days...

?>
<!DOCTYPE html>
<html>
<head>
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<script type="text/javascript" src="../common/jquery.js"></script>
</head>
<body>
<div class="wrapper">
	<header>
		<div id="navc">
			<div class="login_info">
				<div class="dropList">
					<script type="text/javascript">
							function showDropList() 
							{
						    document.getElementById("myDropdown").classList.toggle("showDropList");
							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
							  if (!event.target.matches('.dropListbtn')) {

							    var dropdowns = document.getElementsByClassName("dropdownLists");
							    var i;
							    for (i = 0; i < dropdowns.length; i++) {
							      var openDropdown = dropdowns[i];
							      if (openDropdown.classList.contains('showDropList')) {
							        openDropdown.classList.remove('showDropList');
							      }
							    }
							  }
							}
					</script>
					<button onclick="javascript: showDropList()" class="dropListbtn"><?php echo $data['full_name']; ?></button>
					  	<div id="myDropdown" class="dropdownLists">
					    <a href="profile.php?id=<?php echo $data['user_id']; ?>" class="dropa">Profile Edit</a>
					    <a href="../common/logout.php" class="dropa">LogOut</a>
						</div>
				</div>
			</div>
			<div class="logo"><div id="logo"></div></div>
			<nav>
				<ul>
					<li><a href="home.php">Parties</a></li>
					<li><a href="viewPartyDates.php">Available Dates</a></li>
					<li><a href="BookedParty.php">Booked Parties</a></li>
				</ul>
			</nav>
		</div>
	</header>

	<div class="content">
		<table class='calendar-table'>
			<tr>
				<th>
				<form method="post">
					<input type="text" name="month-year" value="<?php echo "1-".$monthName."-".$year ?>" 
					style="display: none;">
					<button name="pre" id="pre" class="btn" 
						<?php if ($enablePrev) {echo 'disabled="desabled"';} ?> >Prev
					</button>
				</form>
				</th>
				<th colspan="5" > <?php echo $monthName." ".$year ?> </th>
				<th>
				<form method="post">
					<input type="text" name="month-year" value="<?php echo "1-".$monthName."-".$year ?>" style="display: none;">
					<button name="next" id="nex" class="btn">Next</button>
				</form>
				</th>
			</tr>
			<tr id="dayHeader">
				<?php foreach($weekDays as $key => $weekDay){ ?>
					<td class="text-center"><?php echo $weekDay ?></td>
				<?php } ?>
			</tr>
			<tr class="day">
				<?php 
				for ($day=$lastMonthDays-$blank+1; $day <= $lastMonthDays; $day++) { 
					echo "<td class='othersMonthDay'>".$day."</td>";
				}
				for ($day=1; $day <= $daysInMonth ; $day++) { 
					if (checkBookedDate($day, $month, $year, $connect)) {
						echo "<td class='bookedDay'>".$day."</td>";
					}else{
						echo "<td>$day</td>";
					}
					if (($day + $blank) % 7 == 0) {
						echo "</tr>";
						echo "<tr class='day'>";
					}
				}
				for ($day=0; ($day + $blank+$daysInMonth)% 7 != 0 ; $day++) { 
					$d = $day + 1;
					echo "<td class='othersMonthDay'>".$d."</td>";
				}
				?>
			</tr>
		</table>
    </div>

    <footer>
    	<p>&copy; 2017 by Raginda Children Party Place.</p>
    </footer>
  </div>
</body>
</html>