<?php

function addMissingServicesToWeekendPermanence($db_connection) {
    // Step 1: Get all serviceIDs from Collaborateur table grouped by serviceID
    $getServiceIdsQuery = "
    SELECT DISTINCT ServiceID 
    FROM Collaborateur 
    WHERE ServiceID IS NOT NULL
    ";
    $serviceIdsResult = $db_connection->query($getServiceIdsQuery);

    if (!$serviceIdsResult) {
        die("Query failed: " . $db_connection->error);
    }

    while ($row = $serviceIdsResult->fetch_assoc()) {
        $serviceId = $row['ServiceID'];

        // Step 2: Check if the serviceID exists in WeekendPermanence
        $checkServiceQuery = "
        SELECT COUNT(*) as count 
        FROM WeekendPermanence 
        WHERE serviceID = ?
        ";
        $stmt = $db_connection->prepare($checkServiceQuery);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $serviceExists = $row['count'] > 0;

        if (!$serviceExists) {
            // Step 3: ServiceID does not exist in WeekendPermanence, find the lowest idCollaborateur for that serviceID
            $getLowestCollaborateurQuery = "
            SELECT id 
            FROM Collaborateur 
            WHERE ServiceID = ? 
            ORDER BY id ASC 
            LIMIT 1
            ";
            $stmt = $db_connection->prepare($getLowestCollaborateurQuery);
            $stmt->bind_param("i", $serviceId);
            $stmt->execute();
            $result = $stmt->get_result();
            $collabRow = $result->fetch_assoc();

            if ($collabRow) {
                $collaborateurId = $collabRow['id'];

                // Insert the lowest idCollaborateur into WeekendPermanence
                $insertQuery = "
                INSERT INTO WeekendPermanence (collaborateurID, serviceID, weekendDate) 
                VALUES (?, ?, ?)
                ";
                $stmt = $db_connection->prepare($insertQuery);
                
                // Assuming weekendDate is the upcoming weekend; adjust accordingly
                $nextWeekend = (new DateTime())->modify('next Saturday')->format('Y-m-d');
                $stmt->bind_param("iis", $collaborateurId, $serviceId, $nextWeekend);
                
                if ($stmt->execute()) {
                    echo "Collaborateur (ID: $collaborateurId) successfully added to WeekendPermanence for Service (ID: $serviceId).<br>";
                } else {
                    echo "Error adding Collaborateur: " . $db_connection->error . "<br>";
                }

                $stmt->close();
            }
        }
    }
}

addMissingServicesToWeekendPermanence($db_connection);

// $currentDay = '2024-09-02';
$currentDay = date('Y-m-d');
// Calculate the date of the next Saturday
$nextWeekend = date('Y-m-d', strtotime('next sunday'));

// Fetch all services
$servicesResult = $db_connection->query("SELECT id FROM Service");
if (!$servicesResult) {
    die("Query failed: " . $db_connection->error);
}

$services = $servicesResult->fetch_all(MYSQLI_ASSOC);

// Loop through each service to rotate collaborators
foreach ($services as $service) {
    $serviceId = $service['id'];

    // Fetch the current collaborator assigned for the weekend permanence for this service
    $stmt = $db_connection->prepare("
        SELECT collaborateurID, weekendDate 
        FROM WeekendPermanence
        WHERE serviceID = ? 
        ORDER BY weekendDate DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $currentCollaborateur = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($currentCollaborateur) {
        $storedWeekendDate = $currentCollaborateur['weekendDate'];
        $currentCollaborateurID = $currentCollaborateur['collaborateurID'];

        // Check if the current day is after the stored weekendDate
        if ($currentDay > $storedWeekendDate) {
            $stmt = $db_connection->prepare("
                SELECT id FROM Collaborateur 
                WHERE ServiceID = ? AND id > ? AND status = 'Disponible'
                ORDER BY id ASC LIMIT 1
            ");
            $stmt->bind_param("ii", $serviceId, $currentCollaborateurID);
            $stmt->execute();
            $nextCollaborateur = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // If no next collaborator is found, wrap around to the start of the list
            if (!$nextCollaborateur) {
                $stmt = $db_connection->prepare("
                    SELECT id FROM Collaborateur 
                    WHERE ServiceID = ? AND status = 'Disponible'
                    ORDER BY id ASC LIMIT 1
                ");
                $stmt->bind_param("i", $serviceId);
                $stmt->execute();
                $nextCollaborateur = $stmt->get_result()->fetch_assoc();
                $stmt->close();
            }

            // If a next collaborator is found, update the weekend permanence
            if ($nextCollaborateur) {
                $nextCollaborateurID = $nextCollaborateur['id'];

                // Update the weekend permanence with the next collaborator and new weekend date
                $stmt = $db_connection->prepare("
                    UPDATE WeekendPermanence 
                    SET collaborateurID = ?, weekendDate = ? 
                    WHERE collaborateurID = ? AND serviceID = ?
                ");
                $stmt->bind_param("isii", $nextCollaborateurID, $nextWeekend, $currentCollaborateurID, $serviceId);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}
?>
