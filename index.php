<?php
include("common/config.php");
session_start();
if (isset($_SESSION['id'])  && isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "admin") { header('location: admin/admin.php'); }
    else{ header('location: normal/home.php'); }
}
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];		
    // To protect MySQL injection for Security purpose
    $username = stripslashes($username);
    $password = stripslashes($password);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $connect->query($sql);
    $count = $result->num_rows;
    $data = $result->fetch_array();
    
    if ($count > 0) 
    {
    $cookie_tries_name = "tries_" . $username;
    if (isset($_COOKIE[$cookie_tries_name])) {
            $tries = $_COOKIE[$cookie_tries_name];
    }
    if ($data['password'] === $password && $tries < 3) 
    {
        $userType = $data["user_type"];
        $user_id = $data['user_id'];
        $error = "successfully login";
        $_SESSION['id']=$user_id;  //session is already started above
        $_SESSION['type']=$userType;


        if ($userType == "admin") { header("location:admin/admin.php");  }
        else{ header("location:normal/home.php"); }

    }else { $error = set_login_fail_cookie($username); }
    }else { $error = "Username doesnot exist!!"; }
}
?>
<html>
<head>
    <title>Children Party4U | Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
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
            <!---
            <li><a href="#">Welcome</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            !-->
        </ul>
    </nav>
    </div>
</header>

<div class="content">
    <div class="form" id="login-form">
    <form method="post">
        <label> Username: </label>
        <input type='text' id='username' name="username" required="required"/>
        <label> Password: </label>
        <input type='password' id='password' name="password" required="required"/>
        <p><?php if (isset($login_error)) echo $login_error; ?></p>
        <input type='submit' name='login' id="login" value='Login' />
        <input type='reset' name='reset' value='Reset' /><br>
        <span id="warnLogin" style="color:red; "> 
		<?php if (isset($error)){ echo $error; } ?> 
	</span>
        <p class="links_message">Not registered? 
		<a href="registration.php">Create an account</a>
	</p>
    </form>
    </div>
</div>
<footer>
    <p>&copy; 2017 by Raginda Children Party Place.</p>
</footer>
</div>
</body>
</html>
<?php
    function set_login_fail_cookie($username){
        $cookie_tries_name = "tries_" . $username;
        $cookie_duration_name = "duration_" . $username;
        $cookie_duration_time = "time_" . $username;

        $error = "The password is incorrect!!";

        if (isset($_COOKIE[$cookie_tries_name])) 
        {
            $login_tries = $_COOKIE[$cookie_tries_name];
            $login_block_duration = $_COOKIE[$cookie_duration_name];

            if ($login_tries < 3 ) 
            {
                $login_tries ++;
                setcookie($cookie_tries_name, $login_tries, time() + ($login_block_duration * 60), "/");
                $rem_time = 4 - $login_tries;
                $error .= "You have ". $rem_time . " tries left.";
            }else
            {
                if ($login_tries == 3) {
                        $date = get_date("+". $login_block_duration." minutes"); 
                        setcookie($cookie_duration_time, $date, time() + (86400 * 30), "/");
                        $error = "You have been banned for ". $login_block_duration .
				 " minutes. Try again later at: ".$date;

                setcookie($cookie_tries_name, $login_tries+1, time() + (($login_block_duration) * 60), "/");
                }else{
                        $error ="You have been banned for ".$login_block_duration ." minutes. "
				."Try again later at: " .$_COOKIE[$cookie_duration_time];
                }
            }
        }elseif (isset($_COOKIE[$cookie_duration_name]) && !isset($_COOKIE[$cookie_tries_name])) {
            $login_block_duration = $_COOKIE[$cookie_duration_name] + 15;
            setcookie($cookie_duration_name, $login_block_duration, time() + 86400, "/");
            if ($login_block_duration > 45) {
                    $login_tries = 4;
                    setcookie($cookie_tries_name, $login_tries, time() + 86400, "/");
            }else{
                    $login_tries = 1;
                    setcookie($cookie_tries_name, $login_tries, time() + ($login_block_duration * 60), "/");
            }
        }else
        {
            $login_tries = 1;
            $login_block_duration = 15;
            setcookie($cookie_tries_name, $login_tries, time() + ($login_block_duration * 60), "/");
            setcookie($cookie_duration_name, $login_block_duration, time() + 86400, "/");
        }
    return $error;
    }
    function get_date($time=null, $format='H:i:sa')
    {
        date_default_timezone_set("Asia/Kathmandu");
        if(empty($time)) { return date($format); }
        else { return date($format, strtotime($time));}
    }
?>