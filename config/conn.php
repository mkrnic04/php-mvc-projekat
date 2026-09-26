<?php 

include_once($_SERVER["DOCUMENT_ROOT"] . "/optics/config/common.php");

try {

    $conn = new PDO("mysql:host=".HOST.";dbname=".DB_NAME.";charset=utf8", USERNAME, PASSWORD);

    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
    echo $ex->getMessage();
}