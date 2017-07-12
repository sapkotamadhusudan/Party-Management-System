<?php
include("common/config.php");

if(isset($_POST['register'])){
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
$sql="INSERT INTO users VALUES(NULL,'$fname','$email','$username','$password','$phone','$gender','normal')";

    $connect->query($sql);

    header("location:index.php");
}
?>
<html>
<head>
<title>Children Party4U | Registration</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="common/jquery.js"></script>
</head>
<body>
<div class="wrapper">
<header>
    <div id="navc">
    <div class="login_info">
        <a href="index.php" class="loginRe">Login</a>
        <a href="registration.php" class="loginRe">Register</a>
    </div>
    <div class="logo"><div id="logo"></div></div>
    <nav>
        <ul> 
        <!--
        <li><a href="#">Welcome</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact Us</a></li>
        !-->
        </ul>
    </nav>
    </div>
</header>
<div class="content">
    <div class="form" id="register-form">
    <form id="registerform" method="post">
        <fieldset>
        <p>Registration Process..</p>
        <p>
        <label> Full Name: </label>
        <input type='text' id='fname' name="fname" onkeyup="firstNameV()" />
        <span id="warnfn" class="message"></span>
        </p>

        <p>
        <label> Email: </label>
        <input type='email' id='email' name="email" onkeyup="emailV()" />
        <span id="warnEmail" class="message" ></span>
        </p>

        <p>
        <label> Username: </label>
        <input type='text' id='username' name="username" onkeyup="usernameV()" />
        <span id="warnU" class="message" ></span>
        </p>

        <p>
        <label> Password: </label>
        <input type='password' id='password' name="password" onkeyup="passwordV()"/>
        <span id="warnPw" class="message" ></span>
        </p>

        <p>
        <label> Gender: </label>
        <input type="radio" name="gender" id="male" value="1" checked="checked" /><label for="male">Male</label>
        <input type="radio" name="gender" id="female" value="2" /><label for="female">Female</label>
        <input type="radio" name="gender" id="others" value="3" /><label for="others">Others</label> 
        <span id="warnGender" class="message" ></span>
        </p>

        <p>
        <label> Phone: </label>
        <input type='text' id='phone' name="phone" onkeyup="phoneV()" />
        <span id="warnPh" class="message" ></span>
        </p>

        <p>
        <input type='reset' name='reset' value='Reset' />										
        <button class="register" name='register' onclick="return validateForm();" >Register</button>
        </p>

        <p><?php if (isset($registor_error)) echo $registor_error; ?></p>
        <span id="warnRegistor" ></span>
        <p class="links_message">Already have account ? <a href="index.php">Login</a></p>	
        </fieldset>	
    </form>
    </div>
    <div style="clear:both"></div>
</div>
<footer>
    <p>&copy; 2017 by Raginda Children Party Place.</p>
</footer>
</div>
</body>
</html>

<script type="text/javascript">

function firstNameV()  
{ 
    var fname = document.getElementById('fname'); var warnf = document.getElementById("warnfn");
    warnf.innerHTML = "";

    var letters = /^[A-Za-z ]+$/;  
    if(fname.value.match(letters)) { return true;}  
    else { warnf.innerHTML = "Please enter valid name!!"; return false; }
} 

function emailV()  
{  
    var email = document.getElementById('email');  var warnEmail = document.getElementById("warnEmail");
    warnEmail.innerHTML = "";

    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
    if(email.value.match(mailformat))  { return true; }  
    else {warnEmail.innerHTML="Please enter valid email!!"; return false; }  
} 

var isUserNameValid = false;
function usernameV()  
{   
    var username = document.getElementById('username');  var warnU = document.getElementById('warnU');
    warnU.innerHTML = "";
    warnU.style.color="red";

    var letters = /^[A-Za-z0-9]+$/;  

    var req;
    if(window.XMLHttpRequest) { req = new XMLHttpRequest();	}
    else 					  { req = new ActiveXObject("Microsoft.XMLHTTP"); }
    req.onreadystatechange = function()
    {
    if(req.readyState==4)
    {
    var isUsernameExist = req.responseText;

    if(username.value.match(letters)) 
    {
    isUserNameValid = true; 
    if (isUsernameExist > 0) { warnU.innerHTML = 'Username already exist!!'; isUserNameValid = false;}
    else { warnU.innerHTML = 'Username is available!!'; warnU.style.color="green"; isUserNameValid = true;}
    }  
    else { document.getElementById('warnU').innerHTML = 'Username must have alphabet/numbers characters only!!'; 
    warnU.style.color="red"; isUserNameValid = false; }

    return isUserNameValid;
    }
    }
    req.open("GET", "common/checkUsername.php?us="+username.value);
    req.send();
}


function passwordV()  
{  
    var password = document.getElementById('password'); var warnPw = document.getElementById('warnPw');
    warnPw.innerHTML = "";

    var passwordLength = password.value.length;  
    if (passwordLength == 0 ||passwordLength >= 12 || passwordLength < 7)  
    {  
    warnPw.innerHTML = "Password should not be empty / length should be between "+6+" to "+12;  
    return false; 
    }else{ return true; }  
}  

function phoneV()  
{   
    var phone = document.getElementById('phone'); var warnPh = document.getElementById('warnPh');
    warnPh.innerHTML = "";

    var numbers = /^[0-9]+$/;  
    if(phone.value.match(numbers)) {  return true; }  
    else { warnPh.innerHTML = 'Phone must be numeric only!!'; return false; }  
}

function validateForm(){
    var fn = firstNameV(); var em = emailV(); var us = isUserNameValid; var pw = passwordV(); var ph = phoneV(); 
    if (fn != false && em!= false && us != false  && pw != false && ph != false) { return true;}
    else { return false; }
}
</script>