<?php
    include("../common/config.php");
    session_start();// Starting Session

    if (!isset($_SESSION['id'])  && !isset($_SESSION['type'])) {
        header("location:../index.php");
    }
    if ($_SESSION['type'] != "admin") {
        header('location: ../index.php'); // Redirecting To Home Page
    }
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE user_id='$id'";
    $result = $connect->query($sql);
    if ($result!= null) { $data = $result->fetch_array(); }
    else{ $data = null; }
?>
<!DOCTYPE html>
<html>
<head>
<title>Children Party4U | Admin</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<script type="text/javascript">

function showDropList() { document.getElementById("myDropdown").classList.toggle("showDropList"); }

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
    <div class="party_heading">
        <h2>Parties</h2>
    </div>
<div class="parties">
    <table>
        <tr>
            <th>S.N.</th>
            <th>Type Of Party</th>
            <th>Description</th>
            <th>Cost Per Child</th>
            <th>Length Of Party</th>
            <th>Maximum No. of Child</th>
            <th>Products/Services</th>
            <th>Photo</th>
            <th></th>
        </tr>
        <?php
        $sql = "SELECT * FROM party";
        $result = $connect->query($sql);
        $count = 1;
        while ($data = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $count; $count++; ?></td>
            <td><?php echo $data['party_name']; ?></td>
            <td><?php echo $data['description']; ?></td>
            <td>(£) <?php echo $data['cost']; ?></td>
            <td><?php echo $data['length']; ?> minute</td>
            <td><?php echo $data['no_of_child']; ?></td>
            <td><?php echo $data['service']; ?></td>
            <td>
                <img src="../uploads/<?php echo $data['file_name']; ?>" style="width: 100px; height:100px;" 
                class="p_img">
            </td>
            <td><a href="updateParty.php?id=<?php echo $data['id']; ?>">Update</a><br>
                   <a href="deleteParty.php?id=<?php echo $data['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
</div>

<footer>
    <p>&copy; 2017 by Raginda Children Party Place.</p>
</footer>
</div>
</body>
</html>