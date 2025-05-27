<?php
     session_start();
     header('Content-Type: application/json');

     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "burger_place";

     $conn = new mysqli($servername, $username, $password, $dbname);
     if ($conn->connect_error) {
         echo json_encode(['success' => false, 'message' => 'Connection failed']);
         exit;
     }

     $session_id = session_id();
     $stmt = $conn->prepare("DELETE FROM cart_items WHERE session_id = ?");
     $stmt->bind_param("s", $session_id);
     $stmt->execute();
     $stmt->close();

     echo json_encode(['success' => true]);
     $conn->close();
     ?>