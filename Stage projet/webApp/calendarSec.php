<?php
include("session.php");
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
    <style>
    h1 {
        text-align: center;
        margin-bottom: 5%;
        font-weight: 800;
        color: #2c3e50;
    }
    .main-calender{
        padding: 5%;
        height: 100%;
    }
    .calendar {
        width: 100%;
        height: max-content;
        margin: 0 auto;
        font-size: 20px;
        border-collapse: collapse;
    }

    .calendar th {
        background: #0f3443;
        color: white;
        padding: 10px;
        text-align: center;
    }

    .calendar td {
        width: 14.28%;
        height: 60px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ddd;
        padding: 10px;
        box-sizing: border-box;
    }

    .calendar td.weekend {
        background-color: #f8c471;
    }

    .calendar td.current-day {
        background-color: #f39a42;
        color: white;
        font-weight: bold;
    }

    .calendar td:not(.weekend):not(.current-day) {
        background-color: #ecf0f1;
    }
    </style>
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
        <div class="main-calender">
        <?php
            date_default_timezone_set('Africa/Casablanca');
            $month = date('m');
            $year = date('Y');
            $current_day = date('j');
            $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
            $month_name = date('F', $first_day_of_month);

            $day_of_week = date('N', $first_day_of_month);
            echo "<h1>$month_name $year</h1>";
            echo "<table class='calendar'>";
            echo "<tr>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                </tr>";
            echo "<tr>";
            if ($day_of_week > 1) {
                for ($i = 1; $i < $day_of_week; $i++) {
                    echo "<td></td>";
                }
            }
            for ($day = 1; $day <= $total_days; $day++) {
                $class = '';
                if (($day_of_week + $day - 2) % 7 == 5 || ($day_of_week + $day - 2) % 7 == 6) {
                    $class = 'weekend';
                }
                if ($day == $current_day) {
                    $class .= ' current-day';
                }
                
                echo "<td class='$class'>$day</td>";
                if (($day_of_week + $day - 1) % 7 == 0) {
                    echo "</tr><tr>";
                }
            }
            if (($day_of_week + $total_days - 1) % 7 != 0) {
                for ($i = 0; $i < (7 - (($day_of_week + $total_days - 1) % 7)); $i++) {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
            echo "</table>";
        ?>
    </div>
    </div>
</div>
<script src="../script/script.js">
</script>
</body>
</html>
