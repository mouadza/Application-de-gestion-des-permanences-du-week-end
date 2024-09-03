<?php
include('session.php');

// Fetch the current collaborator for each service
$currentCollaboratorsStmt = $db_connection->prepare("
    SELECT 
        Service.id AS service_id,
        Collaborateur.id AS current_collab_id,
        MAX(WeekendPermanence.weekendDate) AS latest_weekend_date
    FROM 
        Service
    LEFT JOIN 
        WeekendPermanence ON WeekendPermanence.serviceID = Service.id
    LEFT JOIN 
        Collaborateur ON WeekendPermanence.collaborateurID = Collaborateur.id
    GROUP BY 
        Service.id
");
$currentCollaboratorsStmt->execute();
$currentResult = $currentCollaboratorsStmt->get_result();

$currentCollaborators = [];
while ($currentRow = $currentResult->fetch_assoc()) {
    $currentCollaborators[$currentRow['service_id']] = $currentRow['current_collab_id'];
}
$currentCollaboratorsStmt->close();

// Fetch all services with their secretaries and collaborators
$query = "
SELECT 
    Service.id AS service_id,
    Service.description AS service_description,
    Secretaire.firstname AS sec_fname,
    Secretaire.lastname AS sec_lname,
    Secretaire.Matricule AS sec_matricule,
    Secretaire.adresse AS sec_adresse,
    Secretaire.phoneNumber AS sec_phone,
    Collaborateur.id AS collab_id,
    Collaborateur.firstname AS collab_fname,
    Collaborateur.lastname AS collab_lname,
    Collaborateur.Matricule AS collab_matricule,
    Collaborateur.adresse AS collab_adresse,
    Collaborateur.phoneNumber AS collab_phone
FROM 
    Service
LEFT JOIN 
    Secretaire ON Secretaire.ServiceID = Service.id
LEFT JOIN 
    Collaborateur ON Collaborateur.ServiceID = Service.id AND Collaborateur.status = 'Disponible'
ORDER BY 
    Service.id, Collaborateur.id ASC
";

$result = $db_connection->query($query);

$services = [];
while ($row = $result->fetch_assoc()) {
    $service_id = $row['service_id'];
    
    // Ensure each service is initialized in the array even if there is no secretary
    if (!isset($services[$service_id])) {
        $services[$service_id] = [
            'description' => $row['service_description'],
            'secretaire' => [
                'fname' => $row['sec_fname'] ?? '',  // Use null coalescing to handle nulls
                'lname' => $row['sec_lname'] ?? '',
                'matricule' => $row['sec_matricule'] ?? '',
                'adresse' => $row['sec_adresse'] ?? '',
                'phone' => $row['sec_phone'] ?? ''
            ],
            'collaborateurs' => []
        ];
    }

    // Only add collaborators if they exist
    if ($row['collab_id']) {
        $services[$service_id]['collaborateurs'][] = [
            'id' => $row['collab_id'],
            'fname' => $row['collab_fname'],
            'lname' => $row['collab_lname'],
            'matricule' => $row['collab_matricule'],
            'adresse' => $row['collab_adresse'],
            'phone' => $row['collab_phone']
        ];
    }
}

// Reorder collaborators for each service to start with the current one
foreach ($services as $service_id => &$service) {
    if (!empty($service['collaborateurs'])) {
        if (isset($currentCollaborators[$service_id])) {
            $currentCollabId = $currentCollaborators[$service_id];
            $orderedCollaborators = [];
            $remainingCollaborators = [];
            $foundCurrent = false;

            
            foreach ($service['collaborateurs'] as $collaborateur) {
                if ($collaborateur['id'] == $currentCollabId) {
                    $foundCurrent = true;
                }
                if ($foundCurrent) {
                    $orderedCollaborators[] = $collaborateur;
                } else {
                    $remainingCollaborators[] = $collaborateur;
                }
            }
            if ($foundCurrent) {
                $service['collaborateurs'] = array_merge($orderedCollaborators, $remainingCollaborators);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/admin.css">
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
        <a href="admin.php"><ion-icon name="home-outline"></ion-icon>Home</a>
        <a href="adminProfile.php"><ion-icon name="person-circle-outline"></ion-icon>Profile</a>
        <a href="secr.php"><ion-icon name="body-outline"></ion-icon>Secretaire</a>
        <a href="collab.php"><ion-icon name="people-outline"></ion-icon>Collaborateur</a>
        <a href="calendar.php"><ion-icon name="calendar-outline"></ion-icon>Calendar</a>
    </div>
    <div class="sidebar-logout" id="logout-area">
        <img src="<?php echo $_SESSION['image'] ;?>" alt="User Image">
        <span><?php echo $_SESSION['fname']. "  " .$_SESSION['lname'];?></span>
        <div class="logout-options" id="logout-options">
            <a href="adminProfile.php"><ion-icon name="person-circle-outline"></ion-icon>View Profile</a>
            <a href="logout.php"><ion-icon name="log-out-outline"></ion-icon>Log Out</a>
        </div>
    </div>
</div>
<div class="main">
<div class="container">
    <h1>Aperçu du service</h1>
    <?php 
    foreach ($services as $service_id => &$service): ?>
        <div class="service-card">
            <div class="service-header">
                <h2>Service "<?php echo htmlspecialchars($service['description']); ?>"</h2>
                <div class="secretaire-info">
                    <?php if (!empty($service['secretaire']['fname']) || !empty($service['secretaire']['lname'])): ?>
                        <p><strong>Secretaire:</strong> <?php echo htmlspecialchars($service['secretaire']['fname'] . " " . $service['secretaire']['lname']); ?></p>
                        <p><strong>Matricule:</strong> <?php echo htmlspecialchars($service['secretaire']['matricule']); ?></p>
                        <p><strong>Adresse:</strong> <?php echo htmlspecialchars($service['secretaire']['adresse']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($service['secretaire']['phone']); ?></p>
                    <?php else: ?>
                        <p><strong>No Secretaire assigned.</strong></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="collaborators-list">
                <h3>Collaborateurs:</h3>
                <?php if (!empty($service['collaborateurs'])): ?>
                    <ul>
                        <?php foreach ($service['collaborateurs'] as $collaborateur): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($collaborateur['fname'] . " " . $collaborateur['lname']); ?></strong><br>
                                Matricule: <?php echo htmlspecialchars($collaborateur['matricule']); ?><br>
                                Adresse: <?php echo htmlspecialchars($collaborateur['adresse']); ?><br>
                                Phone: <?php echo htmlspecialchars($collaborateur['phone']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-collaborators">No collaborateurs found for this service.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>
<script src="../script/script.js"></script>
</body>
</html>
