<?php
include("session.php");
include("crud.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/serv.css">
    <link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css">
    <link rel="icon" href="../icons/logo-ocp.jpg" type="image/y-icon">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>OCP</title>
</head>
<body class ="body">
<div class="sidebar">
    <ion-icon name="menu-outline" id="btn"></ion-icon>
    <div class="logo">
        <img src="../icons/logo-ocp.jpg" alt="Logo">
    </div>
    <div class="sidebar-menus">
        <a href="secretaire.php"><ion-icon name="home-outline"></ion-icon>Home</a>
        <a href="secProfile.php"><ion-icon name="person-circle-outline"></ion-icon>Profile</a>
        <a href="collabSec.php"><ion-icon name="people-outline"></ion-icon>Collaborateur</a>
        <a href="calendarSec.php"><ion-icon name="calendar-outline"></ion-icon>Calendar</a>
    </div>
    <div class="sidebar-logout" id="logout-area">
    <ion-icon name="person-circle-outline" id="person"></ion-icon>
        <span><?php echo $_SESSION['fname']. "  " .$_SESSION['lname'];?></span>
        <div class="logout-options" id="logout-options">
            <a href="secProfile.php"><ion-icon name="person-circle-outline"></ion-icon>View Profile</a>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon>Log Out</a>
        </div>
    </div>
</div>
<div class="main">
    <div class="main-cont">
    <div class="side-main">
    <img src="../icons/profile.jpg" alt="">
    <input type="file" id="image" name="image" style="display:none;" onchange="document.getElementById('imageForm').submit();"><br>
    <p>Secretaire</p>
    <span><?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?></span>

<form id="imageForm" method="POST" enctype="multipart/form-data">
    <input type="file" id="image" name="image" style="display:none;" onchange="this.form.submit();">
</form>
</div>
        <div class="right-main">
            <h3>Profile Info</h3>
            <hr class=".styled-line">
            <div class="info">
            <p>Service:<span><?php echo $_SESSION['desc']  ?></span></p>
            <p>Email:<span><?php echo $_SESSION['email']?></span></p>
            <p>Phone:<span><?php echo $_SESSION['phone']?></span></p>
            <p>Adresse:<span><?php echo $_SESSION['adresse']?></span></p>
            <div class="button-edit" id="editbtn"><ion-icon name="create"></ion-icon>Edit</div>
            </div>
        </div>
    </div>
    <div class="popup-overlay" id="popup-overlay">
        <div class="popup-form" id="popup-form">
            <form method="post">
                <h2>Edit Info</h2>
                <?php if (!empty($errorMesage)) : ?>
                    <p style="color: red;"><?php echo $errorMesage; ?></p>
                <?php elseif (!empty($successMesage)) : ?>
                    <p style="color: green;"><?php echo $successMesage; ?></p>
                <?php endif; ?>
                <label for="fname">First Name:</label>
                <input value="<?php echo $_SESSION['fname']?>" type="text" id="fname" name="fname"><br>
                <label for="lname">Last Name:</label>
                <input value="<?php echo $_SESSION['lname']?>" type="text" id="lname" name="lname"><br>
                <label for="email">Email:</label>
                <input value="<?php echo $_SESSION['email']?>" type="text" id="email" name="email"><br>
                <label for="phone">Phone:</label>
                <input value="<?php echo $_SESSION['phone']?>" type="text" id="phone" name="phone"><br>
                <label for="adresse">Adresse:</label>
                <input value="<?php echo $_SESSION['adresse']?>" type="text" id="adresse" name="adresse"><br>
                <button name="sub">Save</button>
            </form> 
        </div>
    </div>
</div>

<script src="../script/script.js">
</script>
</body>
</html>
