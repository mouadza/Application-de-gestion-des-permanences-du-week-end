<?php
include('./webApp/session.php');
include('./webApp/weekendPerm.php');
function displayWeekendDates() {
    $today = new DateTime();
    $saturday = clone $today;
    $saturday->modify('Saturday this week');
    $sunday = clone $saturday;
    $sunday->modify('Sunday this week');
    $saturdayDate = $saturday->format('d/m/Y');
    $sundayDate = $sunday->format('d/m/Y');
    echo "$saturdayDate - $sundayDate";
}
$query = "
SELECT 
    Service.id AS service_id,
    Service.description AS service_description,
    Collaborateur.firstname AS collab_fname,
    Collaborateur.lastname AS collab_lname,
    Collaborateur.Matricule AS collab_matricule,
    Collaborateur.adresse AS collab_adresse,
    Collaborateur.phoneNumber AS collab_phone
FROM 
    Service
LEFT JOIN 
    Collaborateur ON Collaborateur.ServiceID = Service.id
INNER JOIN 
    WeekendPermanence ON WeekendPermanence.collaborateurID = Collaborateur.id
    ORDER BY Service.id  ASC
";

// Execute the query and check for errors
$result = $db_connection->query($query);
if (!$result) {
    die("Query failed: " . $db_connection->error);
}

// Initialize the services array
$services = [];

// Fetch results
while ($row = $result->fetch_assoc()) {
    $service_id = $row['service_id'];
    if (!isset($services[$service_id])) {
        $services[$service_id] = [
            'description' => $row['service_description'],
            'collaborateurs' => []
        ];
    }
    if ($row['collab_fname']) {
        $services[$service_id]['collaborateurs'][] = [
            'fname' => $row['collab_fname'],
            'lname' => $row['collab_lname'],
            'matricule' => $row['collab_matricule'],
            'adresse' => $row['collab_adresse'],
            'phone' => $row['collab_phone']
        ];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10"> 
    <link rel="stylesheet" href="./style/acceuil.css">
    <link rel="icon" href="./icons/logo-ocp.jpg" type="image/y-icon">
    <title>Acceuil</title>
</head>
<body>
    <nav>
    <div class="navbar">
        <div class="logo">
            <img src="./icons/logo-ocp.jpg" alt="Logo">
        </div>
        <div class="center-text">
            Planning Pour le week-end
            <span> <?php 
            displayWeekendDates()?></span>
        </div>
        <div >
            <a href="./webApp/login.php" class="login-button" >Log In</a>
        </div>
    </div>
    </nav>
    <div class="main">
    <div class="container">
    <h1>Aperçu du Guard</h1>
    <?php foreach ($services as $service): ?>
        <div class="service-card">
            <div class="service-header">
                <h2>Service <?php echo $service['description']; ?></h2>
            </div>
            <div class="collaborators-list">
                <?php if (!empty($service['collaborateurs'])): ?>
                    <ul>
                        <?php foreach ($service['collaborateurs'] as $collaborateur): ?>
                            <li>
                                <div class="collaborator-name">
                                    <strong><?php echo $collaborateur['fname'] . " " . $collaborateur['lname']; ?></strong>
                                </div>
                                <div class="collaborator-info">
                                    <div class="info-item">Matricule: <span><?php echo $collaborateur['matricule']; ?></span></div>
                                    <div class="info-item">Adresse: <span><?php echo $collaborateur['adresse']; ?></span></div>
                                    <div class="info-item">Phone: <span><?php echo $collaborateur['phone']; ?></span></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>

</body>
</html>

