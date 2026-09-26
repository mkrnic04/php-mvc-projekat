<?php

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';

extract($_POST);

$insert = postProduct($pname, $pdesc, $ppath, $pprice, $pcat);


if ($insert) {
    echo json_encode(array('success' => true, 'message' => 'Product inserted successfully'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to insert product'));
}
?>
