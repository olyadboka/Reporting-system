<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dbname = "hmreportsystem";
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    residence_id VARCHAR(20) UNIQUE NOT NULL,
    fname VARCHAR(50) NOT NULL,
    mname VARCHAR(50),
    fathersName VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    birthdate DATE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    fatherFullName VARCHAR(100) NOT NULL,
    fatherPhone VARCHAR(15) NOT NULL,
    motherFullName VARCHAR(100) NOT NULL,
    motherPhone VARCHAR(15) NOT NULL,
    emergencyName VARCHAR(100) NOT NULL,
    emergencyPhone VARCHAR(15) NOT NULL,
    photo LONGBLOB
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.";
} else {
    die("Error creating table: " . $conn->error);
}

// Alter the table to ensure the 'photo' column is of type LONGBLOB
$sql = "ALTER TABLE users MODIFY photo LONGBLOB";
if ($conn->query($sql) === TRUE) {
    echo "Column 'photo' modified to LONGBLOB successfully.";
} else {
    die("Error modifying column: " . $conn->error);
}

$conn->close();