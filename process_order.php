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
         $customer = $data['customer'] ?? [];

         if (empty($cart) || empty($customer['name']) || empty($customer['address'])) {
             echo json_encode(['success' => false, 'message' => 'Invalid data']);
             exit;
         }

         // Calculate total amount
         $total_amount = 0;
         foreach ($cart as $item) {
             $total_amount += $item['price'] * $item['quantity'];
         }

         // Insert order
         $stmt = $conn->prepare("
             INSERT INTO orders (customer_id, customer_name, customer_address, total_amount, created_at)
             VALUES (?, ?, ?, ?, NOW())
         ");
         $customer_id = 1; // Placeholder
         $stmt->bind_param("issd", $customer_id, $customer['name'], $customer['address'], $total_amount);
         $stmt->execute();
         $order_id = $conn->insert_id;
         $stmt->close();

         // Insert order items
         $stmt = $conn->prepare("
             INSERT INTO order_items (order_id, menu_item_id, quantity, price)
             VALUES (?, ?, ?, ?)
         ");
         foreach ($cart as $item) {
             $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
             $stmt->execute();
         }
         $stmt->close();

         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'message' => 'Invalid request']);
     }

     $conn->close();
     ?>