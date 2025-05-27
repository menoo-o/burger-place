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

     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $data = json_decode(file_get_contents('php://input'), true);
         $cart = $data['cart'] ?? [];
         $session_id = session_id();

         // Clear existing cart items for this session
         $stmt = $conn->prepare("DELETE FROM cart_items WHERE session_id = ?");
         $stmt->bind_param("s", $session_id);
         $stmt->execute();
         $stmt->close();

         // Insert new cart items
         $stmt = $conn->prepare("INSERT INTO cart_items (session_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
         foreach ($cart as $item) {
             $stmt->bind_param("siid", $session_id, $item['id'], $item['quantity'], $item['price']);
             $stmt->execute();
         }
         $stmt->close();

         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'message' => 'Invalid request']);
     }

     $conn->close();
     ?>