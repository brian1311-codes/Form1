<?php

$host = "localhost";
$dbname = "form_db";
$username = "root";
$password = "";


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name  = htmlspecialchars($_POST['name']);
$phone = htmlspecialchars($_POST['phone']);
$email = htmlspecialchars($_POST['email']);


$sql = "INSERT INTO contacts (name, phone, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $phone, $email);

if ($stmt->execute()) {
    header("Location: thankyou.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
