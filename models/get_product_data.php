<?php

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';

if(isset($_POST['id'])) {
    //id proizvoda
    $id = $_POST['id'];
    
    // podaci o tom proizvodu
    $product = getProductById($id); 
    
    if($product) {
        echo json_encode($product);
    } else {
        echo json_encode(array('error' => 'Product not found'));
    }
} else {
    echo json_encode(array('error' => 'ID parameter not existing'));
}
?>