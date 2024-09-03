<?php
$emailValue = "";
$lnameValue = "";
$fnameValue = "";
$phoneNumber = "";
$adresse = "";
$passwordValue = "";
$errorMesage = "";
$successMesage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["Submit"])) {
        $emailValue = $_POST["email"];
        $lnameValue = $_POST["lname"];
        $fnameValue = $_POST["fname"];
        $phoneNumber = $_POST["PhoneNumber"];
        $adresse = $_POST["adresse"];
        $passwordValue1 = $_POST["password"];
        $passwordValue2 = $_POST["password1"];
        $image = $_FILES['image']['tmp_name']; // Get the image file
        $imageData = file_get_contents($image); // Read the image file into a string

        if (empty($emailValue) || empty($fnameValue) || empty($lnameValue) || empty($phoneNumber) || empty($passwordValue1)) {
            $errorMesage = "All fields must be filled out!";
        } else if (strlen($passwordValue2) < 8) {
            $errorMesage = "Password must contain at least 8 characters.";
        } else if (preg_match("/[A-Z]+/", $passwordValue2) == 0) {
            $errorMesage = "Password must contain at least one capital letter!";
        } else if ($passwordValue1 != $passwordValue2) {
            $errorMesage = "Passwords do not match!";
        } else {
            // Include the connection file
            include("Connection.php");

            // Create an instance of the Connection class
            $conn = new Connection();
            $conn->selectDatabase("StageOCP");

            // Insert data into Admin table using prepared statement
            $query = "INSERT INTO Admin (firstname, lastname, email, phoneNumber, adresse, password, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->getConnection()->prepare($query);
            $hashedPassword = password_hash($passwordValue1, PASSWORD_DEFAULT); // Hash the password

            if ($stmt) {
                $stmt->bind_param("sssssss", $fnameValue, $lnameValue, $emailValue, $phoneNumber, $adresse, $hashedPassword, $imageData);

                if ($stmt->execute()) {
                    $successMesage = "Admin registered successfully!";
                    // Clear the form fields after successful registration
                    $emailValue = $lnameValue = $fnameValue = $phoneNumber = $adresse = $passwordValue1 = $passwordValue2 = "";
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
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
    /* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Form Container */
form {
    background-color: #ffffff;
    padding: 20px 40px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    text-align: center; /* Center the text and image */
}

/* Form Heading */
h2 {
    text-align: center;
    color: #333333;
    margin-bottom: 20px;
}

/* Input Fields */
input[type="text"],
input[type="password"],
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #cccccc;
    border-radius: 4px;
}

/* Input Focus Effect */
input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Submit Button */
button {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #45a049;
}
</style>
<body>

<form method="post" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
    <h2>Admin Forms</h2>
    <?php if (!empty($errorMesage)) : ?>
        <p style="color: red;"><?php echo $errorMesage; ?></p>
    <?php elseif (!empty($successMesage)) : ?>
        <p style="color: green;"><?php echo $successMesage; ?></p>
    <?php endif; ?>
    <input type="text" id="fname" name="fname" placeholder="First Name"><br>
    <input type="text" id="lname" name="lname" placeholder="Last Name"><br><br>
    <input type="text" id="fname" name="email" placeholder="Email"><br>
    <input type="text" id="fname" name="PhoneNumber" placeholder="Tele"><br>
    <input type="text" id="fname" name="adresse" placeholder="Adresse"><br>
    <input type="password" id="fname" name="password" placeholder="Password"><br>
    <input type="password" id="fname" name="password1" placeholder="Confirm Password"><br>
    <input type="file" id="image" name="image"><br> 
    <button name="Submit">Submit</button>
</form> 

</body>
</html>
