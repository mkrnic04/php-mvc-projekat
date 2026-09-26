<?php
include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';

$productName = $_POST['product_name'];
$featureName = $_POST['feature_name'];
$featureValue = $_POST['feature_value'];


$result = addFeatureToProduct($productName, $featureName, $featureValue);

if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Feature added successfully'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to add feature'));
}
?>
