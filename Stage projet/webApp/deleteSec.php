<?php
$id = $_GET['id'];
include("Connection.php");
$conn = new Connection();
$conn->selectDatabase("StageOCP");
$db_connection = $conn->getConnection();
$query1 = "DELETE FROM Secretaire WHERE id=?";
$stmt1 = $db_connection->prepare($query1);
if ($stmt1) {
    $stmt1->bind_param("i", $id);
    if ($stmt1->execute()) {
        header('Location: secr.php');
        exit();
    } else {
        echo "Error: " . $stmt1->error;
    }
    $stmt1->close();
} else {
    echo "Failed to prepare the statement!";
}
?>
