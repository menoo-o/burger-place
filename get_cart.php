<?php
     session_start();
     header('Content-Type: application/json');

     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "burger_place";

     $conn = new mysqli($servername, $username, $password, $dbname);
     if ($conn->connect_error) {
         echo json_encode(['error' => 'Connection failed']);
         exit;
     }

     $session_id = session_id();
     $stmt = $conn->prepare("
         SELECT ci.menu_item_id AS id, ci.quantity, ci.price, m.name, m.description, m.image
         FROM cart_items ci
         JOIN menu_items m ON ci.menu_item_id = m.id
         WHERE ci.session_id = ?
     ");
     $stmt->bind_param("s", $session_id);
     $stmt->execute();
     $result = $stmt->get_result();

     $cart = [];
     while ($row = $result->fetch_assoc()) {
         $cart[] = $row;
     }

     echo json_encode($cart);
     $stmt->close();
     $conn->close();
     ?>