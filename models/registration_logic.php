<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';

function insertUserData() {
    global $conn;
    extract($_POST);
    try {
        // Insert za location
        $sql = "INSERT INTO location(country, city, address, postal_code) 
                VALUES (:country, :city, :address, :postal_code)";
        $insert_location = $conn->prepare($sql);
        $insert_location->bindParam(":country", $country);
        $insert_location->bindParam(":city", $city);
        $insert_location->bindParam(":address", $address); 
        $insert_location->bindParam(":postal_code", $postal);
        $insert_location->execute();

        $id_location = $conn->lastInsertId();

        // Insert za user
        $sql = "INSERT INTO users(first_name, last_name, email, phone, id_role, id_location, password) 
                VALUES(:fname, :lname, :email, :phone, :id_role, :id_location, :password)";
        $id_role = 2;
        $pass_hashed = md5($pass);

        $insert_user = $conn->prepare($sql);
        $insert_user->bindParam(":fname", $fname);
        $insert_user->bindParam(":lname", $lname);
        $insert_user->bindParam(":email", $email); 
        $insert_user->bindParam(":phone", $phone);
        $insert_user->bindParam(":id_role", $id_role);
        $insert_user->bindParam(":id_location", $id_location);
        $insert_user->bindParam(":password", $pass_hashed);
        $insert_user->execute();

    } catch (PDOException $ex) {
        setFlash('error', "Error: " . $ex->getMessage());
        header('Location: ../index.php?page=registration');
        exit;
    }
}

if (isset($_POST['btnReg'])) {
    $errors = [];

    // Validacija
    if (empty($_POST['fname']) || !preg_match("/^[A-Z][a-z]*$/", $_POST['fname'])) {
        $errors[] = "Invalid first name";
    }
    if (empty($_POST['lname']) || !preg_match("/^[A-Z][a-z]*$/", $_POST['lname'])) {
        $errors[] = "Invalid last name";
    }
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email, must contain @";
    }
    if (empty($_POST['phone']) || !preg_match("/^[0-9]{3}\/[0-9]{7}$/", $_POST['phone'])) {
        $errors[] = "Invalid phone format";
    }
    if (empty($_POST['country']) || !preg_match("/^[A-Z][a-zA-Z\s]*$/", $_POST['country'])) {
        $errors[] = "Invalid country format";
    }
    if (empty($_POST['city']) || !preg_match("/^[A-Z][a-zA-Z\s]*$/", $_POST['city'])) {
        $errors[] = "Invalid city format";
    }
    if (empty($_POST['address']) || !preg_match("/^[A-Z][a-zA-Z\s0-9]*$/", $_POST['address'])) {
        $errors[] = "Invalid address format";
    }
    if (empty($_POST['postal']) || !preg_match("/^[0-9]{6}$/", $_POST['postal'])) {
        $errors[] = "Postal code must contain exactly 6 numbers";
    }
    if (empty($_POST['pass']) || !preg_match("/^[a-zA-Z0-9!@#$%^&*()_+]+$/", $_POST['pass'])) {
        $errors[] = "Password can only contain letters, numbers, and special characters (!@#$%^&*()_+)";
    }
    if ($_POST['pass'] !== $_POST['passConf']) {
        $errors[] = "Passwords do not match";
    }

    
    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email = :email";
        $result = $conn->prepare($query);
        $result->bindParam(":email", $_POST['email']);
        $result->execute();
        if ($result->rowCount() > 0) {
            $errors[] = "Email already exists";
        }
    }

    if (empty($errors)) {
        try {
            insertUserData();
            setFlash('success', "Registration successful! You can now log in.");
            header('Location: ../index.php?page=registration');
            exit;
        } catch (PDOException $ex) {
            setFlash('error', "Error: " . $ex->getMessage());
            header('Location: ../index.php?page=registration');
            exit;
        }
    } else {
        $errorMessages = implode("<br>", $errors);
        setFlash('error', "Validation failed. Please correct the errors:<br>$errorMessages");
        header('Location: ../index.php?page=registration');
        exit;
    }
}
?>
