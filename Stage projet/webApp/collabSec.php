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
    <div class="main-container">
    <h2>Listes des collaborateurs du Service <?php echo $_SESSION['desc'] ;?></h2>
    <?php if (!empty($errorMesage)) : ?>
                    <p style="color: red;"><?php echo $errorMesage; ?></p>
                <?php elseif (!empty($successMesage)) : ?>
                    <p style="color: green;"><?php echo $successMesage; ?></p>
                <?php endif; ?>
        <div class="add-btn" id="editbtn">
        <ion-icon name="add-circle"></ion-icon>
        Ajouter
        </div>
        <table>
    <thead>
        <tr>
            <th>Prenom</th>
            <th>Nom</th>
            <th>Matricule</th>
            <th>Email</th>
            <th>Addresse</th>
            <th>Telephone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($result5 as $row) {
            echo "<tr>
                <td>{$row['firstname']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['Matricule']}</td>
                <td>{$row['email']}</td>
                <td>{$row['adresse']}</td>
                <td>{$row['phoneNumber']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='editCollabBySecr.php?id={$row['id']}' id='ed'><ion-icon name='create'></ion-icon></a>
                   <a href='deleteCollabBySecr.php?id={$row['id']}' id='del'><ion-icon name='trash'></ion-icon></a>
                </td>
            </tr>";
        }
        
        ?>
    </tbody>
</table>
</div>
<div class="confirmation-overlay" id="confirmation-overlay">
    <div class="confirmation-popup">
        <h2>Are you sure you want to delete ?</h2>
        <button id="confirm-delete">Yes, Delete</button>
        <button id="cancel-delete">Cancel</button>
    </div>
</div>
<div class="popup-overlay" id="popup-overlay">
        <div class="popup-form" id="popup-form">
            <form id="add-collab-form" method="post">
                <h2>Add Collaborateur</h2>
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname"><br>
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname"><br>
                <label for="email">Adresse:</label>
                <input type="text" id="email" name="adresse"><br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone"><br>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email"><br>
                <button name="sub1">Submit</button>
            </form> 
        </div>
    </div>
</div>
<script src="../script/script.js">
</script>
<script>
</script>
</body>
</html>
