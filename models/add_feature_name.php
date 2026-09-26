<?php
ob_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';
include $_SERVER['DOCUMENT_ROOT'] . '/optics/models/functions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feature_name'])) {
    $featureName = $_POST['feature_name'];

    $result = addFeatureName($featureName);

    if ($result) {
        header("Location: ".$_SERVER['HTTP_REFERER']."");
        exit();
    } else {
        echo "Failed to add feature";
    }
} else {
    echo "Invalid request";
}
ob_end_flush();
?>
