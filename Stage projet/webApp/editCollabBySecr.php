<?php
$id = $_GET['id'];
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
<body>
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
    <div class="main-cont-form">
<form method="post" enctype="multipart/form-data">
<h2>Edit Collaborateur</h2>
    <?php if (!empty($errorMesage)) : ?>
        <p style="color: red;"><?php echo $errorMesage; ?></p>
    <?php elseif (!empty($successMesage)) : ?>
        <p style="color: green;"><?php echo $successMesage; ?></p>
    <?php endif; ?>
    <input value="<?php echo $row3['firstname']; ?>" type="text" id="fname" name="fname"><br>
    <input value="<?php echo $row3['lastname']; ?>" type="text" id="lname" name="lname"><br>
    <input value="<?php echo $row3['phoneNumber']; ?>" type="text" id="fname" name="phone"><br>
    <input value="<?php echo $row3['adresse']; ?>" type="text" id="fname" name="adresse"><br>
    <select id="service" name="status">
        <?php
        if($row3['status'] === "Disponible"){
            echo"
            <option value='1' selected> $row3[status]</option>
            <option value='3'>Indisponible</option>";
        }else{
            echo"
            <option value='1' selected> $row3[status]</option>
            <option value='2'>Disponible</option>";
        }
        ?>
    </select><br>
    <button name="sub2">Save</button>

</form> 
</div>
</div>
<script src="../script/script.js">
</script>
</body>
</html>
