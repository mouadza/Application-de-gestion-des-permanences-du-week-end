<?php
// get matricule unique
function MatrUnique($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// **************************** Table Service ********************************
// select all services
$query = "SELECT id, description FROM Service";
$stmt = $db_connection->prepare($query);
$stmt->execute();
$services = $stmt->get_result();
// **************************** Table Collaborateur ********************************
// Select  all Collaborateur
$query = "SELECT c.*, s.description AS service_description 
          FROM collaborateur c 
          JOIN Service s ON c.ServiceID = s.id";
$stmt2 = $db_connection->prepare($query);
$stmt2->execute();
$result2 = $stmt2->get_result();
//Insert Collaborateur
$lnameValue = "";
$fnameValue = "";
$phoneNumber = "";
$serviceID = "";
$adresse = "";
$errorMesage = "";
$successMesage = "";
$matricule = MatrUnique();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["Submit1"])) {
        $lnameValue = $_POST["lname"];
        $fnameValue = $_POST["fname"];
        $phoneNumber = $_POST["phone"];
        $email = $_POST["email"];
        $adresse = $_POST["adresse"];
        $serviceID = isset($_POST['servDesc']) ? $_POST['servDesc'] : '';
        if (empty($fnameValue) || empty($lnameValue) || empty($phoneNumber) || empty($adresse) || empty($serviceID) || empty($email)) {
            $errorMesage = "All fields must be filled out!";
        } else {
            $query = "INSERT INTO collaborateur (firstname, lastname, matricule, email, phoneNumber, adresse, ServiceID) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db_connection->prepare($query);

            if ($stmt) {
                $stmt->bind_param("sssssss", $fnameValue, $lnameValue, $matricule, $email, $phoneNumber, $adresse, $serviceID);
                if ($stmt->execute()) {
                    $successMesage = "Collaborateur Added successfully!";

                    $lnameValue = $fnameValue = $phoneNumber = $adresse = $serviceID = "";

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit(); // Stop further execution
                } else {
                    $errorMesage = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errorMesage = "Failed to prepare the statement!";
            }
        }
    }
}
// select collaborateur by id
$stmt3 = $db_connection->prepare("SELECT * FROM Collaborateur WHERE id = ?");
$stmt3->bind_param("s", $id);
$stmt3->execute();
$result4 = $stmt3->get_result();
if($result4->num_rows > 0){
    $row3 = $result4->fetch_assoc();
}
$stmt4 = $db_connection->prepare("SELECT * FROM Collaborateur WHERE ServiceID = ?");
$stmt4->bind_param("s", $_SESSION['ServiceID']);
$stmt4->execute();
$result5 = $stmt4->get_result();
// update collaborateur 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["Submit2"])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adresse'];
    $serviceID = $_POST['ServiceID']; 
    if(empty($fname) || empty($lname) || empty($phone) || empty($adresse)){
        $errorMesage = "All fields must be filled out!";
    }else{      
        $query = "UPDATE collaborateur SET firstname=?, lastname=?, phoneNumber=?, adresse=?, ServiceID=? WHERE id=?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("sssssi", $fname, $lname, $phone, $adresse, $serviceID, $id);

        if ($stmt->execute()) {
            header('Location: collab.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
}
// **************************** Table Secretaire ********************************
// Select all secretaire
$query1 = "SELECT sec.*, s.description AS service_description 
          FROM Secretaire sec
          JOIN Service s ON sec.ServiceID = s.id";
$stmt3 = $db_connection->prepare($query1);
$stmt3->execute();
$result3 = $stmt3->get_result();
// Insert secretaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["Submit3"])) {
        $matricule = MatrUnique();
        $firstname = $_POST['fname'];
        $lastname = $_POST['lname'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $adresse = $_POST['adresse'];
        $pass = $_POST['password'];
        $pass1 = $_POST['password1'];
        $serviceID = isset($_POST['serviceID']) ? $_POST['serviceID'] : '';

        // Validate input fields
        if (empty($firstname) || empty($lastname) || empty($email) || empty($phoneNumber) || empty($adresse) || empty($serviceID)) {
            $errorMesage = "All fields are required.";
        } else if (strlen($pass1) < 8) {
            $errorMesage = "Password must contain at least 8 characters.";
        } else if (preg_match("/[A-Z]+/", $pass1) == 0) {
            $errorMesage = "Password must contain at least one capital letter!";
        } else if ($pass != $pass1) {
            $errorMesage = "Passwords do not match!";
        } else {
            $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);

            // Create a new database connection
            $conn = new Connection();
            $conn->selectDatabase("StageOCP");
            $db_connection = $conn->getConnection();

            // Check if email is already registered
            $stmt = $db_connection->prepare("SELECT email FROM Secretaire WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $errorMesage = "This email is already registered.";
                } else {
                    $stmt->close();

                    // Check if the ServiceID already has a secretaire
                    $stmt = $db_connection->prepare("SELECT ServiceID FROM Secretaire WHERE ServiceID = ?");
                    if ($stmt) {
                        $stmt->bind_param("i", $serviceID);
                        $stmt->execute();
                        $stmt->store_result();

                        if ($stmt->num_rows > 0) {
                            $errorMesage = "This service already has a secretaire.";
                        } else {

                            // Insert new secretaire
                            $stmt = $db_connection->prepare("INSERT INTO Secretaire (Matricule, firstname, lastname, email, phoneNumber, adresse, password, ServiceID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($stmt) {
                                $stmt->bind_param("sssssssi", $matricule, $firstname, $lastname, $email, $phoneNumber, $adresse, $hashedPassword, $serviceID);
                                if ($stmt->execute()) {
                                    $successMessage = "Secretaire added successfully!";
                                } else {
                                    $errorMesage = "Error: " . $stmt->error;
                                }
                            } else {
                                $errorMesage = "Failed to prepare the insert statement!";
                            }
                        }
                    } else {
                        $errorMesage = "Failed to prepare the service check statement!";
                    }
                }
            } else {
                $errorMesage = "Failed to prepare the email check statement!";
            }
        }
    }
}
// select secretaire by id
$stmt3 = $db_connection->prepare("SELECT * FROM Secretaire WHERE id = ?");
$stmt3->bind_param("s", $id1);
$stmt3->execute();
$result = $stmt3->get_result();
if($result->num_rows > 0){
    $row4 = $result->fetch_assoc();
}
// update secretaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["Submit4"])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $adresse = $_POST['adresse'];
        $email = $_POST['email'];
        $serviceID = $_POST['ServiceID']; 

        if (empty($fname) || empty($lname) || empty($phone) || empty($adresse)) {
            $errorMesage = "All fields must be filled out!";
        } else {
            // Check if the service already has a secretaire
            $query = "SELECT COUNT(*) as count FROM Secretaire WHERE ServiceID = ? AND id != ?";
            $stmt = $db_connection->prepare($query);
            $stmt->bind_param("ii", $serviceID, $id1); // exclude the current secretaire being edited
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $errorMesage = "This service already has a secretaire assigned.";
            } else {
                // Proceed with the update if no conflict is found
                $query = "UPDATE Secretaire SET firstname=?, lastname=?, email=?, phoneNumber=?, adresse=?, ServiceID=? WHERE id=?";
                $stmt = $db_connection->prepare($query);
                $stmt->bind_param("ssssssi", $fname, $lname, $email, $phone, $adresse, $serviceID, $id1);

                if ($stmt->execute()) {
                    header('Location: secr.php');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["sub1"])) {
        $lnameValue = $_POST["lname"];
        $fnameValue = $_POST["fname"];
        $phoneNumber = $_POST["phone"];
        $adresse = $_POST["adresse"];
        $email = $_POST["email"];
        if (empty($fnameValue) || empty($lnameValue) || empty($phoneNumber) || empty($adresse) || empty($email)) {
            $errorMesage = "All fields must be filled out!";
        } else {
            $query = "INSERT INTO collaborateur (firstname, lastname, matricule, email, phoneNumber, adresse, ServiceID) VALUES (?, ?, ?, ?, ?, ?,?)";
            $stmt = $db_connection->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ssssssi", $fnameValue, $lnameValue, $matricule, $email, $phoneNumber, $adresse, $_SESSION['ServiceID']);
                if ($stmt->execute()) {
                    // Success message
                    $successMesage = "Collaborateur Added successfully!";

                    // Clear form fields
                    $lnameValue = $fnameValue = $phoneNumber = $adresse = $serviceID = "";

                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit(); // Stop further execution
                } else {
                    $errorMesage = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errorMesage = "Failed to prepare the statement!";
            }
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["sub2"])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $adresse = $_POST['adresse'];
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        if (empty($fname) || empty($lname) || empty($phone) || empty($adresse)) {
            $errorMesage = "All fields must be filled out!";
        } else {
            $query = "UPDATE Collaborateur SET firstname=?, lastname=?, phoneNumber=?, adresse=?, status=? WHERE id=?";
            $stmt = $db_connection->prepare($query);
            $stmt->bind_param("sssssi", $fname, $lname, $phone, $adresse, $status, $id);

            if ($stmt->execute()) {
                header('Location: collabSec.php');
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

    }
}


?>
