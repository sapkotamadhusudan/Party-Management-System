<?php 
session_start();// Starting Session

if (!isset($_SESSION['id'])  && !isset($_SESSION['type'])) { header("location:../index.php"); }
if ($_SESSION['type'] != "admin") {  header('location: ../index.php'); }

include("../common/config.php");
$id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE user_id='$id'";
$result = $connect->query($sql);
$data = $result->fetch_array(); 

if(isset($_POST['add'])){

    $name = $_POST['party_type'];
    $desc = $_POST['desc'];
    $cost = $_POST['cpchild'];
    $length = $_POST['length'];
    $maxChild = $_POST['no_of_child'];
    $products = $_POST['products'];

    $message = "";
    $target_dir = "../uploads/";
    $imageFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
    $uploadOk = 1;		
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    if($check !== false) { $uploadOk = 1; $message = "File is an image - " . $check["mime"] . "."; 
    } else { $uploadOk = 0; $message = "File is not an image."; }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") { $uploadOk = 0;
        $message = "Sorry, only JPG, JPEG, PNG files are allowed.";   
    }
    $file_name = $name."_".$id.".".$imageFileType;
    $file_name = preg_replace('/\s+/', '', $file_name);
    $target_file = $target_dir . $file_name;
    if ($uploadOk != 0) {// if everything is ok, upload image
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    }
    if ($uploadOk !=0) {
	$sql = "INSERT INTO party VALUES (NULL,'$name', '$cost', '$length', '$maxChild', '$products', 
		'$desc',  '$file_name')";
        $connect->query($sql);
        header("location:../admin.php");
    }
    $add_error = $message;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Children Party4U | Party Add</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script type="text/javascript">

    function showDropList()  { document.getElementById("myDropdown").classList.toggle("showDropList");  }

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
        <li><a href="admin.php">Parties</a></li>
        <li><a href="addParty.php">Add Party</a></li>
    </ul>
    </nav>
</div>
</header>

<div class="content">
    <div class="form" style="width: 400px;">
    <form method="post" enctype="multipart/form-data">
        <p>Party Registration process..</p>
        <p>
        <label> Party Type: </label>
        <input type='text' id='party_type' name="party_type"  />
        <span id="warnpt"></span>
        </p>

        <p>
        <label> Cost per Child(£): </label>
        <input type='text' id='cpchild' name="cpchild" />
        <span id="warnCost"></span>
        </p>

        <p>
        <label> Length of Party(minute): </label>
        <input type='text' id='length' name="length" />
        <span id="warnLength"></span>
        </p>

        <p>
        <label> Max No. Of Children: </label>
        <input type='number' id='no_of_child' name="no_of_child" />
        <span id="warnChild" style="color: "></span>
        </p>

        <p>
        <label>Products/Services: </label>
        <textarea id='products' name="products" rows="3" cols="50"></textarea>
        <span id="warnProduct" style="color: "></span>
        </p>

        <p>
        <label> Description: </label>
        <textarea id='desc' name="desc" rows="5" cols="50"></textarea>
        <span id="warnDesc"></span>
        </p>

        <p>
        <label>Photo: </label>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <span id="warnProduct" style="color: "></span>
        </p>

        <p>					
        <input type='submit' id="add" name='add' value='add' />
        <input type='reset' name='reset' value='Reset' />
        </p>

        <p><?php if (isset($add_error)) echo $add_error; ?></p>
        <span  class="message" id="warnUpdate" style="color: "></span>
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
