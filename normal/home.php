<?php
	include("../common/config.php");
	session_start();// Starting Session
	if(!isset($_SESSION['id']) &&  !isset($_SESSION['type'])){
		header('location: ../index.php'); // Redirecting To Home Page
	}
	if ($_SESSION['type'] != "normal") {
		header('location: ../index.php'); // Redirecting To Home Page
 	}
 	// Storing user details to $userData
	$user_id = $_SESSION['id'];	
	$sql = "SELECT * FROM users WHERE user_id='$user_id'";
	$result = $connect->query($sql);
	$userData = $result->fetch_array();


	 function convertCurrency($amount, $from, $to)
	 {
	    $url  = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
	    $data = file_get_contents($url);
	    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
	   $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
	   return round($converted, 3);
	}

	function getCurrencySymbole($type)
	{
		if ($type == "GBP") {
			return "£";
		}else if ($type == "USD") {
			return "$";
		}else if ($type == "EUR") {
			return "€";
		}
	}

	 $cctype = "GBP";
	 $amount; $type;
	if (isset($_POST['changeCurrency'])) {
		$cctype = $_POST['currency'];
	}


?>
<!DOCTYPE html>
<html>
<head>
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<script type="text/javascript" src="../common/jquery.js"></script>
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
</head>
<body>
<div class="wrapper">
	<header>
		<div id="navc">
			<div class="login_info">
				<div class="dropList">
					<button onclick="javascript: showDropList()" class="dropListbtn"><?php echo $userData['full_name']; ?></button>
					  	<div id="myDropdown" class="dropdownLists">
					    <a href="profile.php?id=<?php echo $userData['user_id']; ?>" class="dropa">Profile Edit</a>
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
		<div id="contentHeader">
			<div id="currencyForm">
				<form method="post">
					<label>Choose Currency Type: </label>
					<select name="currency">
				      <option value="GBP" <?php if($cctype == "GBP"){ echo 'selected="selected"';} ?> >POUND</option>
				      <option value="USD" <?php if($cctype == "USD"){ echo 'selected="selected"';} ?> >AMERICAL DOLLER</option>
				      <option value="EUR" <?php if($cctype == "EUR"){ echo 'selected="selected"';} ?> >EURO</option>
				    </select>
				    <button name="changeCurrency" id="btn">Change</button>
				</form>
			</div>
		</div>
	    <div class="parties_list">
	    	<?php
	    		$sql = "SELECT * FROM party";
	    		$result = $connect->query($sql);
	    		while ($pdata = $result->fetch_assoc()) {
	    			?>
	    			<div class="one_party">
	    				<div class="party_details">
	    					<div class="pd">
		    					
		    					<h2 class="party_head">Party: <?php echo $pdata['party_name']; ?></h2>
		    					<p>
		    						<b>Cost per Children: </b>
		    							<?php
		    									$costType; 
		    								if ($cctype == "GBP") {
			    								$type = "£";
			    								$costType = "GBP";
			    								$amount = $pdata['cost'];
			    							}else{
			    								$costType = $cctype;
			    								$type = getCurrencySymbole($cctype);
			    								$amount =convertCurrency($pdata['cost'], "GBP", $cctype);
			    							}
		    							echo $type." ".$amount; 
		    							?>
		    					</p>
		    					<p><b>Duration of party: </b><?php echo $pdata['length']; ?> minutes</p>
		    					<p><b>Max. no. of children: </b><?php echo $pdata['no_of_child'];?> childrens</p>
		    					<p><b>Services/Products: </b><br><?php echo $pdata['service']; ?> </p>
		    					<p id="book_btn">
		    						<button onclick="javascript:openBooking(<?php echo $pdata['id']; ?>, '<?php echo $pdata['party_name']; ?>',
		    						<?php echo $pdata['no_of_child']; ?>, '<?php echo $costType; ?>',
		    						<?php echo $amount; ?> );"
		    						>Book</button>
		    					</p>
	    					</div>
	    				</div>
	    				<div class="party_image1">
	    					<img src="../uploads/<?php echo $pdata['file_name']; ?>">
	    					<p><b>Description: </b><br><?php echo $pdata['description']; ?> </p>
	    				</div>
	    			</div>
	    			<hr>
	    			<?php
	    		}
	    	?>

	    </div>
    </div>
    <div id="blackPart">
    	<div id="popUpBooking">
    		<div id="bookingContent">
    			<h3 style="margin: 0;">Booking Process of <span id="ptype"></span>...</h3>
			<form method="post" action="booking.php" id="bookingF">
		    	<fieldset id="bookingForm">
		    		<input type="text" name="userId" value="<?php echo $userData['user_id']; ?>" style="display: none;">
		    		<input type="text" name="userName" value="<?php echo $userData['full_name']; ?>" style="display: none;">
		    		<input type="text" name="userMail" value="<?php echo $userData['email']; ?>" style="display: none;">
    				<input type="text" name="party_id" id="party_id" style="display: none;">
    				<input type="number" name="costPerChild1" id="costPerChild1" style="display: none;">
    				<input type="text" name="costType" id="costType" style="display: none;">
    				<p>
    				<label>No. of children attending</label>
    				<input type="number" name="no_of_children_to_book" id="childNo" min="0" 
    					   onkeyup="javascript: calculateCost(); checkMaxChild();">
    				<span class="error" id="errorChild" style="display: none;"></span>
    				</p>
    				<p>
    				<label id="cl">Total Cost</label>
    				<input type="text" name="totalCostOfBooking" id="cost" readonly="readonly">
    				</p>
    					<p>
    					<label>Check Available Date</label><br>
    					<select name="year" id="y" class="opt">
    						<option>none</option>
    					</select>
    					<select name="month" id="m" class="opt">
    						<option>none</option>
    					</select>
    					<select name="day" id="d" class="opt">
    						<option>none</option>
    					</select>
    					<button id="check" onclick="javascript: return checkDate()">Check</button>
    					</p>
    					<span class="error" id="errorDate" style="display: none;"></span>
    					<input type="submit" name="bookedParty" value="book" onclick="return checkBooking();">
    				</fieldset>
			</form>
    		</div>
    		<a href="javascript:void(0)" class="closeButton" onclick="javascript: closeBooking()">&times;</a>
    	</div>

        <div id="childrenInfo">
            <div id="childrenInfoContent">
                <h2>You had not provided your children information yet</h2>
                <form method="post" style="min-height: 200px;">
                    <input type="text" name="parent_id" style="display: none;">
                    <p>Please Let us know you child info. for future offers!</p>

                    <div id="childDOB">
                        <div>
                            <label>Enter Your Child Date Of Birth</label>
                            <input type="date" name="childDOB[]" class="dob">
                            <button id="add_dob">Add Field</button>
                        </div>
                    </div>
                    <div style="width: 100%; float: left; margin-top: 20px;">
                    	<div style="width: 50%; height: 50px; float: left;">
                    		<a href="#" onclick=" closeForm()" id="skipEvent">Skip</a>
                    	</div>
                    	<div  style="width: 50%; height: 50px; float: left;">
                    		<input type="submit" name="submitDOB" value="submit" id="childBtn" onclick="javascript: return checkDOB()">
                    	</div>
                    </div>
                    
                </form>
            </div>
            <a href="javascript:void(0)" class="closeButton" onclick="javascript: closeForm()">&times;</a>
        </div>
    </div>
        <?php 
    	////////////////// booking php /////////////////////////////
    	
    	if (isset($_POST['submitDOB'])) {
    		$dobDate = $_POST['childDOB'];
    		$parent_id = $_POST['parent_id'];
    		$sql = "INSERT INTO childrens VALUES"; //(NULL,date,pid) ,(NULL, date, pid)";
    		foreach ($dobDate as $key) {
    			$sql .= "(NULL,$key,$parent_id) ,";
    		}
    		$newSql=substr_replace($sql, "", -1);
    		$connect->query($newSql);
    	}

    	 ?>
    <script type="text/javascript">
        //////////////////////////  children Date of birth validation /////////////////////////////
        $(document).ready(function (){
            var userId = <?php echo $user_id;  ?>;
            var isChildInfoThere = false;
            var req;

            if(window.XMLHttpRequest) { req = new XMLHttpRequest(); }
            else                      { req = new ActiveXObject("Microsoft.XMLHTTP"); }

            req.onreadystatechange = function()
            {
                if(req.readyState==4)
                {
                    var isDateAvailable = req.responseText;
                    if (isDateAvailable  == 0) 
                    {
                        document.getElementById("blackPart").style.height = "100%";
		                document.getElementById("childrenInfo").style.display = "block";
		                document.getElementById("user_id").value = userId;
                    }else{
                    	document.getElementById("blackPart").style.height = "0px";
            			document.getElementById("childrenInfo").style.display = "none";
                    }
                }
            }
            req.open("GET", "checkChildrenInfo.php?userId="+userId);
            req.send();
        });

        function closeForm() {
            document.getElementById("blackPart").style.height = "0px";
            document.getElementById("childrenInfo").style.display = "none";
        }

        $(document).ready(function (){
            var max_child_field = 3
            var wrapper = $("#childDOB");
            var addChildDOB = $("#add_dob");

            var x = 1;
            $(addChildDOB).click(function (e) {
                e.preventDefault();
                if (x < max_child_field) {
                    x++;
                    $(wrapper).append("<div><input type='date' name='childDOB[]' class='dob'> <button class='remove_dob'>Remove Field</button></div>");

                    $(wrapper).on("click", ".remove_dob", function(e){
                        e.preventDefault();
                        $(this).parent('div').remove();
                        x--;
                    });
                }
            });

         });

	    function closeChildInfo() {
	    	document.getElementById("blackPart").style.height = "0px";
		    document.getElementById("popUpBooking").style.display = "none";

	    }
/////////////////////// booking javascript starts here  ////////////////////////////////////////////////
    	function calculateCost(){
    		 $("#errorChild").css({"display" : "none"});
    		var childNo = document.getElementById("childNo").value;
    		var cpc = document.getElementById("costPerChild1").value;
    		document.getElementById("cost").value = childNo*cpc;
    	}
    		var maxClild1;	
    	function getCurrencySymbole(type)
		{
			if (type == "GBP") {
				return "£";
			}else if (type == "USD") {
				return "$";
			}else if (type == "EUR") {
				return "€";
			}
		}				
    	function openBooking(id, partyType, maxClild, currencyType, costPerChildren) {
		    document.getElementById("blackPart").style.height = "100%";
		    document.getElementById("popUpBooking").style.display = "block";
		    document.getElementById("party_id").value = id;
		    document.getElementById("childNo").max = maxClild; maxClild1 = maxClild;
		    document.getElementById("costPerChild1").value = costPerChildren;
		    document.getElementById("costType").value = currencyType;
		    document.getElementById("cl").innerHTML = "Total Cost in("+getCurrencySymbole(currencyType)+")";
		    document.getElementById("ptype").innerHTML = partyType;
		}

		function closeBooking() {
		    document.getElementById("blackPart").style.height = "0px";
		    document.getElementById("popUpBooking").style.display = "none";
		    document.getElementById("childNo").value = "";
		    document.getElementById("cost").value = "";
		   // $('#y').empty().append('none');
		    $('#m').empty().append('none');
		    $('#d').empty().append('none');
		    $("#errorDate").css({"display" : "none"});
		    $("#errorChild").css({"display" : "none"});
		}

		var isChildVaildated;
		var isDateValidated;

		$(function(){
			var now  = new Date();
			var year = now.getFullYear();
			for (var i = year; i < year+6; i++) { $('#y').append($('<option>', { value: i, text : i })); }
		});

		$('#y').on('change', function (e) {
			$("#errorDate").css({"display" : "none"});
		    $('#m').empty().append('none');

		    var selectedYear = $('#y').find(":selected").text();
		    //get current date
		    var now  = new Date();
			var month = now.getMonth();
			var year = now.getFullYear();
			var index = 0;
			if (year == selectedYear) { index = month;}
			$('#m').append($('<option>', { value: "none",  text : "none"}));
			for (var i = index; i < 12; i++) {	
				var monthN = getMonthName(i); var m = i+1; 
				$('#m').append($('<option>', { value: m , text : monthN }));}
		});

		$('#m').on('change', function (e) {
			$("#errorDate").css({"display" : "none"});
		    $('#d').empty().append('none');
		    var selectedMonth = $('#m').find(":selected").text();
		    var selectedYear = $('#y').find(":selected").text();

		    var now  = new Date();
			var month = now.getMonth();
			var year = now.getFullYear();
			var day = now.getDate();
			var index = 1;
			if (year == selectedYear && getMonthName(month) == selectedMonth) { index = day;}
			for (var i = index; i <= getDaysOfMonth(year, month); i++) { $('#d').append($('<option>', { value: i, text : i })); }
		});

		function checkDate() {
			var day = $("#d").val();
			var month1 = $("#m").val();
			var year = $("#y").val();
			if (year == "null" || year == "none" || month1 =="null" || month1 == "none" || day == "none" || day == "NaN") { 
				isDateValidated = false;
				$("#errorDate").css({"display" : "block", "color" : "red", "height" : "30px"});
                document.getElementById("errorDate").innerHTML = "please choose a date";
			}
			else{
			var month = parseInt(month1) ;
			var sdate = year+"-"+month+"-"+day;
 			var req;

            if(window.XMLHttpRequest) { req = new XMLHttpRequest(); }
            else                      { req = new ActiveXObject("Microsoft.XMLHTTP"); }

            req.onreadystatechange = function()
            {
                if(req.readyState==4)
                {
                    var isDateAvailable = req.responseText;
                    var errorD = document.getElementById("errorDate");
                    if (isDateAvailable  == 1) 
                    {
                    	isDateValidated = false;
                        $("#errorDate").css({"display" : "block", "color" : "red", "height" : "30px"});
                        errorD.innerHTML = "date is not available";
                    }else
                    {
                    	isDateValidated = true;
                        $("#errorDate").css({"display" : "block", "color" : "green", "height" : "30px"});
                        errorD.innerHTML = "date is available";
                    }

                }
            }
            req.open("GET", "checkBookingDate.php?date="+sdate);
            req.send();
        	}
            return false;
		}

		function checkMaxChild(){
			var mc = document.getElementById('childNo').value;
			if (mc > maxClild1) 
			{	document.getElementById("errorChild").innerHTML = "max child is "+maxClild1; 
				document.getElementById('childNo').value = maxClild1;
				return false;
			}
			return true;
		}

		function checkBooking() {
			var isvalid = true;
			var day = $("#d").val();
			var month1 = $("#m").val();
			var year = $("#y").val();
			var child = document.getElementById('childNo').value;

			if (year == "null" || year == "none" || month1 =="null" || month1 == "none" || day == "none" || day == "NaN") 
			{ 
				$("#errorDate").css({"display" : "block", "color" : "red", "height" : "30px"});
                document.getElementById("errorDate").innerHTML = "please choose a date";
				isvalid = false;
			}
			if (child == "" || child == 0 || checkMaxChild() == false) 
			{
				$("#errorChild").css({"display" : "block", "color" : "red", "height" : "30px"});
                document.getElementById("errorChild").innerHTML = "please enter no of child!";
				isvalid =  false; 
			}
			if (isvalid) { 
				if (isDateValidated) { $( "#bookingF" ).submit(); }
				else{ document.getElementById("errorDate").innerHTML = "please choose another date!!";
					return false;
				}
			}
			else{ return false;}
		}

		function checkLeapYear(year)//year in integer
		{
			if (year % 4 ==  0) { return true;}
			else { return false;}
		}

		function getDaysOfMonth(year, month) //year and month in integer
		{
			if (checkLeapYear(year)) { feb = 29;}
			else { feb = 28;}
			var noOfDaysInMonth = new Array(31, feb, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			return noOfDaysInMonth[month];   //returns specific day 
		}

		function getMonthName(month) {
			var months =new Array("January", "February", "March", "April", "May", "June", "July", "August", 
							"September", "October", "November", "December");

			return months[month];
		}
    </script>
    <footer>
    	<p>&copy; 2017 by Raginda Children Party Place.</p>
    </footer>
  </div>

</body>
</html>