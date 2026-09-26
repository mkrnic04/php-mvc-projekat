<?php

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';

    extract($_POST);

    $err = 0;
    
    if ($pname == "" || $pdesc == "" || $ppath == "" || $pprice == "" || $pcat == "") {
        $err++;
    }
    
    if ($err != 0) {
        echo json_encode(array('success' => false, 'message' => 'Error: Invalid inputs'));
    } else {
        $update = updateProduct($id_prod, $pname, $pdesc, $ppath, $pprice, $pcat);
        if ($update) {
            echo json_encode(array('success' => true, 'message' => 'Product updated successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to update product'));
        }
    }


?>

