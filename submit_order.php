<?php
header('Content-Type: text/plain; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kesar_mango";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate input data
$name = $conn->real_escape_string(htmlspecialchars($_POST['name'] ?? ''));
$phone = $conn->real_escape_string(htmlspecialchars($_POST['phone'] ?? ''));
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$quantity = intval($_POST['quantity'] ?? 0);
$address = $conn->real_escape_string(htmlspecialchars($_POST['address'] ?? ''));

// Validate required fields
if (empty($name) || empty($phone) || empty($address) || $quantity <= 0) {
    die("Error: Please fill all required fields with valid data.");
}

// Insert data into the database
$sql = "INSERT INTO orders (name, phone, email, quantity, address) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssis", $name, $phone, $email, $quantity, $address);

if ($stmt->execute()) {
    echo "Order placed successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>