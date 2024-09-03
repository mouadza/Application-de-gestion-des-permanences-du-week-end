<?php
include('session.php');
$serviceID = $_SESSION['ServiceID'];

// Fetch service description
$stmt = $db_connection->prepare("SELECT description FROM Service WHERE id = ?");
$stmt->bind_param("i", $serviceID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['desc'] = $row['description'];
    $stmt->close();
} else {
    die("Service not found.");
}

// Fetch the current collaborator on WeekendPermanence
$stmt_current = $db_connection->prepare("
    SELECT 
        Collaborateur.id AS collab_id,
        WeekendPermanence.weekendDate AS weekend_date
    FROM 
        WeekendPermanence
    JOIN 
        Collaborateur ON WeekendPermanence.collaborateurID = Collaborateur.id
    WHERE 
        WeekendPermanence.serviceID = ?
    ORDER BY 
        WeekendPermanence.weekendDate DESC 
    LIMIT 1
");
$stmt_current->bind_param("i", $serviceID);
$stmt_current->execute();
$current = $stmt_current->get_result()->fetch_assoc();
$stmt_current->close();

if ($current) {
    $currentCollaborateurID = $current['collab_id'];

    // Fetch all collaborators for the service, ordered by ID
    $stmt1 = $db_connection->prepare("
        SELECT 
            id AS collab_id,
            firstname AS collab_fname,
            lastname AS collab_lname,
            Matricule AS collab_matricule,
            adresse AS collab_adresse,
            phoneNumber AS collab_phone
        FROM 
            Collaborateur
        WHERE 
            ServiceID = ?
        ORDER BY 
            id ASC
    ");
    $stmt1->bind_param("i", $serviceID);
    $stmt1->execute();
    $result2 = $stmt1->get_result();
    
    // Fetch all collaborators into an array
    $collaborators = $result2->fetch_all(MYSQLI_ASSOC);
    $stmt1->close();

    // Reorder the collaborators array to start from the current one
    $orderedCollaborators = [];
    $foundCurrent = false;

    // Loop through collaborators to reorder
    foreach ($collaborators as $collaborator) {
        if ($collaborator['collab_id'] == $currentCollaborateurID) {
            $foundCurrent = true;
        }
        if ($foundCurrent) {
            $orderedCollaborators[] = $collaborator;
        }
    }

    // Append the remaining collaborators to the ordered list
    foreach ($collaborators as $collaborator) {
        if ($collaborator['collab_id'] == $currentCollaborateurID) {
            break;
        }
        $orderedCollaborators[] = $collaborator;
    }
} else {
    echo "No current collaborator found.";
}
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
    <h2>Service <?php echo $_SESSION['desc'] ; ?></h2>
    <?php foreach ($orderedCollaborators as $index => $row): ?>
        <div class="user-card">
            <div class="user-card-info">
              <h2><?php echo $row['collab_fname']. " " .$row['collab_lname']?></h2>
              <div class="info-row">
                  <p><span>Matricule:</span> <?php echo $row['collab_matricule']?></p>
                  <p><span>Phone:</span> <?php echo $row['collab_phone']?></p>
                  <p><span>Adresse:</span> <?php echo $row['collab_adresse']?></p>
              </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>
<script src="../script/script.js">
</script>
</body>
</html>
