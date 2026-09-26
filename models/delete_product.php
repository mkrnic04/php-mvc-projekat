<?php
include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';

if(isset($_POST['id'])) {
    
    $id = $_POST['id'];
    
    $delete = deleteProduct($id); 
    
    if($delete) {
        echo json_encode(array('success' => true, 'message' => 'Product deleted successfully'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to delete product'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'ID parameter is missing'));
}
?>




