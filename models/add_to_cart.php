<?php

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';

if (isUserLoggedIn() && getUserRole() == 'member') {
    
    if ($productId = post('product_id')) {

        // inicijalizacija ako nije setovana
        if (!userHasCart()) {
            initUserCart();
        }

        // da li je vec u korpi
        $isProductInCart = isProductInCart($productId);
        
        if ($isProductInCart) {
            incrementProductQuantity($productId);
            echo json_encode(['success' => true, 'message' => 'Product quantity incremented']);
            exit;
        } else {
            // ako nije, dodaj sa svim podacima
            $product = getProductById($productId);
            if ($product) {
                addProductToCart($product);
                echo json_encode(['success' => true, 'message' => 'Product added to cart successfully']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in or does not have permission']);
    exit;
}

?>

