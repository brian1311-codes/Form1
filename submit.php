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
$cname = htmlspecialchars($_POST['c-name']);


$sql = "INSERT INTO contacts (name, phone, email, cname) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $phone, $email, $cname);

if ($stmt->execute()) {
    header("Location: thankyou.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
