<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';


if(isset($_SESSION['user']->id_user)) {
    $userId = $_SESSION['user']->id_user;
} else {
    echo json_encode(['success' => false, 'message' => 'User ID not found in session']);
    exit; 
}



if (isset($_SESSION['user']) && $_SESSION['user']->Role == 'member') {
   
    if (isset($_SESSION['user']->cart) && is_array($_SESSION['user']->cart) && !empty($_SESSION['user']->cart)) {
        //insert
        $date = date('Y-m-d H:i:s'); 

        $orderStmt = $conn->prepare("INSERT INTO orders (id_user, date) VALUES (:userId, :date)");
        $orderStmt->bindParam(':userId', $userId);
        $orderStmt->bindParam(':date', $date);
        $orderStmt->execute();

        $orderId = $conn->lastInsertId();

        if ($orderId) {
            // insert medjutabele
            foreach ($_SESSION['user']->cart as $item) {
                $productId = $item->id_prod;
                $unitPrice = $item->price;
                $quantity = isset($item->quantity) ? $item->quantity : 1;

                $orderDetailStmt = $conn->prepare("INSERT INTO order_details (id_prod, id_ord, unit_price, quantity) VALUES (:productId, :orderId, :unitPrice, :quantity)");
                $orderDetailStmt->bindParam(':productId', $productId);
                $orderDetailStmt->bindParam(':orderId', $orderId);
                $orderDetailStmt->bindParam(':unitPrice', $unitPrice);
                $orderDetailStmt->bindParam(':quantity', $quantity);
                $orderDetailStmt->execute();
            }

            unset($_SESSION['user']->cart);

            echo json_encode(['success' => true, 'message' => 'Order placed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to retrieve order ID']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Your cart is empty']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Please log in as a member to place an order']);
}
?>
