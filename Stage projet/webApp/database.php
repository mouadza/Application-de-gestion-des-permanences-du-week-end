<?php

// Include the connection file
include("Connection.php");

// Create an instance of Connection class
$connection1 = new Connection();

$connection1->createDatabase('StageOCP');   
$connection1->selectDatabase("StageOCP");

$query3 = "
CREATE TABLE IF NOT EXISTS Service (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(30) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$query = "
CREATE TABLE IF NOT EXISTS Admin (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE,
    phoneNumber VARCHAR(30),
    adresse VARCHAR(100),
    password VARCHAR(30),
    image BLOB,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$query1 = "
CREATE TABLE IF NOT EXISTS Secretaire (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Matricule VARCHAR(30),
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE,
    phoneNumber VARCHAR(30),
    adresse VARCHAR(100),
    password VARCHAR(255),
    ServiceID INT(6) UNSIGNED,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ServiceID) REFERENCES Service(id) ON DELETE CASCADE
)";
$query2 = "
CREATE TABLE IF NOT EXISTS Collaborateur (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Matricule VARCHAR(30),
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE,
    phoneNumber VARCHAR(30),
    adresse VARCHAR(100),
    status ENUM('En Service', 'Disponible', 'Indisponible') DEFAULT 'Disponible',
    ServiceID INT(6) UNSIGNED,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ServiceID) REFERENCES Service(id) ON DELETE CASCADE 
)";
$query4 = "
CREATE TABLE IF NOT EXISTS WeekendPermanence (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    collaborateurID INT(6) UNSIGNED,
    weekendDate DATE NOT NULL,
    ServiceID INT(6) UNSIGNED,
    status ENUM('Planifié', 'Terminé', 'Annulé') DEFAULT 'Planifié',
    FOREIGN KEY (collaborateurID) REFERENCES Collaborateur(id) ON DELETE CASCADE,
    FOREIGN KEY (ServiceID) REFERENCES Service(id) ON DELETE CASCADE,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$query5 = "
CREATE TABLE CollaborateurCopy AS SELECT * FROM Collaborateur;
";
$connection1->createTable($query3);
$connection1->createTable($query); 
$connection1->createTable($query1);
$connection1->createTable($query2);
$connection1->createTable($query4);
$connection1->createTable($query5);

?>
