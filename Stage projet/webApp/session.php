<?php
session_start();

include("Connection.php");
$conn = new Connection();
$conn->selectDatabase("StageOCP");
$db_connection = $conn->getConnection();
$errorMesage = "";
$emailValue = "";
$passwordValue = "";

if(isset($_POST["submit"])){
    $emailValue = $_POST["email"];
    $passwordValue = $_POST["password"];
    if(empty($emailValue)){
        $errorMesage = 'Email field required';
    } elseif(empty($passwordValue)){
        $errorMesage = 'Password field required';
    } else {
        $stmt = $db_connection->prepare("SELECT * FROM Admin WHERE email = ?");
        $stmt->bind_param("s", $emailValue);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt1 = $db_connection->prepare("SELECT * FROM secretaire WHERE email = ?");
        $stmt1->bind_param("s", $emailValue);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['fname'] = $row['firstname'];
            $_SESSION['lname'] = $row['lastname'];
            $_SESSION['phone'] = $row['phoneNumber'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['adresse'] = $row['adresse'];
            if ($row['image']) {
                $imageData = base64_encode($row['image']);
                $imageSrc = "data:image/jpeg;base64," . $imageData;
                $_SESSION['image'] = $imageSrc;
            } else {
                $_SESSION['image'] = '../icons/profile.jpg'; // Provide a default image path if no image exists
            }
            
            if(password_verify($passwordValue, $hashedPassword)){
                header("Location: admin.php");
                exit(); // Always call exit() after a redirect
            } else {
                $errorMesage = 'Email or Password is incorrect';
            }
        } else if($result1->num_rows > 0){
                $row1 = $result1->fetch_assoc();
                $hashedPass = $row1['password'];
                $_SESSION['id'] = $row1['id'];
                $_SESSION['fname'] = $row1['firstname'];
                $_SESSION['lname'] = $row1['lastname'];
                $_SESSION['phone'] = $row1['phoneNumber'];
                $_SESSION['email'] = $row1['email'];
                $_SESSION['adresse'] = $row1['adresse'];
                $_SESSION['ServiceID'] = $row1['ServiceID'];
                if(password_verify($passwordValue, $hashedPass)){
                    header("Location: secretaire.php");
                    exit();
                } else {
                    $errorMesage = 'Email or Password is incorrect';
                }
        }else {
          $errorMesage = 'Email or Password is incorrect';
        }
        
        $stmt->close();
        $db_connection->close();
    }
}
if (isset($_POST["Submit"])) {
    $emailValue = $_POST["email"];
    $lnameValue = $_POST["lname"];
    $fnameValue = $_POST["fname"];
    $numberValue = $_POST["phone"];
    $adresseValue = $_POST["adresse"];

    if (empty($emailValue) || empty($fnameValue) || empty($lnameValue) || empty($numberValue) || empty($adresseValue)) {
        $errorMesage = "All fields must be filled!";
    } else {
        $query1 = "UPDATE Admin SET firstname=?, lastname=?, email=?, phoneNumber=?, adresse=? WHERE id=?";
        $stmt1 = $db_connection->prepare($query1);

        if ($stmt1) {
            $stmt1->bind_param("sssssi", $fnameValue, $lnameValue, $emailValue, $numberValue, $adresseValue, $_SESSION['id']);

            if ($stmt1->execute()) {
                $_SESSION['email'] = $emailValue;
                $_SESSION['lname'] = $lnameValue;
                $_SESSION['fname'] = $fnameValue;
                $_SESSION['phone'] = $numberValue;
                $_SESSION['adresse'] = $adresseValue;
                $successMesage = "Information updated successfully!";
            } else {
                $errorMesage = "Error executing query: " . $stmt->error;
            }
            $stmt1->close();
        } else {
            $errorMesage = "Failed to prepare the statement! ";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["sub"])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $adresse = $_POST['adresse'];
        $email = $_POST['email'];

        if (empty($fname) || empty($lname) || empty($phone) || empty($adresse)) {
            $errorMesage = "All fields must be filled out!";
        } else {
                $query = "UPDATE Secretaire SET firstname=?, lastname=?, email=?, phoneNumber=?, adresse=? WHERE id=?";
                $stmt = $db_connection->prepare($query);
                $stmt->bind_param("sssssi", $fname, $lname, $email, $phone, $adresse, $_SESSION['id']);

                if ($stmt->execute()) {
                    $_SESSION['fname'] = $fname;
                    $_SESSION['lname'] = $lname;
                    $_SESSION['phone'] = $phone;
                    $_SESSION['adresse'] = $adresse;
                    $_SESSION['email'] = $email;
                    $successMesage = "Secretaire Updated successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
?>

