<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = ""; // Empty password, as resolved
$dbname = "burger_place";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);

$menuItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}

echo json_encode($menuItems);
$conn->close();
?>