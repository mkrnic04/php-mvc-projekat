<?php

    function getProductsAndCategories(){

        global $conn;

        $sql = "SELECT * FROM products p JOIN categories c ON p.id_cat = c.id_cat";

        $result = $conn->query($sql)->fetchAll(PDO::FETCH_OBJ);

        return $result;

    }

    function getCategoriesWithProductCount() {
        global $conn;
    
        try {
        $query = "SELECT c.name_cat, COUNT(p.id_prod) AS product_count 
                    FROM categories c 
                    LEFT JOIN products p ON c.id_cat = p.id_cat 
                    GROUP BY c.id_cat";
    
        $result = $conn->query($query)->fetchAll();
    
        return $result;
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    function getProductById($id) {
        global $conn;
            
        //$query = "SELECT * FROM products WHERE id_prod = :id";
        $query = "SELECT * FROM products p JOIN categories c ON p.id_cat = c.id_cat WHERE id_prod = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
            
        $product = $stmt->fetch();
            
        return $product;
    }




    //add_to_cart.php

    function isUserLoggedIn() {
        return isset($_SESSION['user']);
    }

    function getUserRole() {
        return $_SESSION['user']->Role ?? null;
    }

    function userHasCart() {
        return isset($_SESSION['user']->cart);
    }

    // inicijalizacija
    function initUserCart() {
        $_SESSION['user']->cart = array();
    }

    // provera da li je proizvod vec u korpi
    function isProductInCart($productId) {
        foreach ($_SESSION['user']->cart as $item) {
            if ($item->id_prod == $productId) {
                return true;
            }
        }
        return false;
    }

    // povecavanje kvantiteta postojeceg prozivoda u korpi
    function incrementProductQuantity($productId) {
        foreach ($_SESSION['user']->cart as $item) {
            if ($item->id_prod == $productId) {
                $item->quantity++;
                break;
            }
        }
    }

    // dodavanje novog proizvoda u korpu
    function addProductToCart($product) {
        $product->quantity = 1;
        $_SESSION['user']->cart[] = $product;
    }


//admin.php
// features za svaki prozivod
function getAllFeatures() {
    global $conn;
    $query = "SELECT p.name_prod AS product_name, f.name_feat, f.value 
              FROM products p
              JOIN feature_item fi ON p.id_prod = fi.id_prod
              JOIN features f ON fi.id_feature = f.id_feature";
    $result = $conn->query($query)->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

// features names za ddl
function getAllDistinctFeatureNames() {
    global $conn;
    $query = "SELECT DISTINCT name_feat FROM features";
    $result = $conn->query($query)->fetchAll(PDO::FETCH_COLUMN);
    return $result;
}

//orders
// function getOrders(){
//     $query = "SELECT o.id_ord, o.date, u.first_name, u.last_name, u.email, p.name_prod AS product_name, od.quantity 
//             FROM orders o 
//             LEFT JOIN users u ON o.id_user = u.id_user
//             LEFT JOIN order_details od ON o.id_ord = od.id_ord
//             LEFT JOIN products p ON od.id_prod = p.id_prod";
//     $stmt = $conn->prepare($query);
//     $stmt->execute();
//     $orders = $stmt->fetchAll(PDO::FETCH_OBJ); 
//     return $orders;
// }

    
//za crud operacije
function postProduct($pname, $pdesc, $ppath, $pprice, $pcat){
    global $conn;

    if(empty($pname) || empty($pdesc) || empty($ppath) || empty($pprice) || empty($pcat)) {
        return false; 
    }

    $selectCategory = $conn->prepare("SELECT id_cat FROM categories WHERE name_cat = :pcat");
    $selectCategory->bindParam(":pcat", $pcat);
    $selectCategory->execute();

    $id_cat = $selectCategory->fetchColumn();

    if (!$id_cat) {
        return false;
    }

    $sql = "INSERT INTO products(name_prod, description, path, price, id_cat) VALUES(:name_prod, :desc, :path, :price, :id_cat)";

    $insert = $conn->prepare($sql);
    $insert->bindParam(":name_prod", $pname);
    $insert->bindParam(":desc", $pdesc);
    $insert->bindParam(":path", $ppath);
    $insert->bindParam(":price", $pprice);
    $insert->bindParam(":id_cat", $id_cat);

    $result = $insert->execute();
    return $result;
    
}

function updateProduct($id, $pname, $pdesc, $ppath, $pprice, $pcat) {
    global $conn;

    if(empty($pname) || empty($pdesc) || empty($ppath) || empty($pprice) || empty($pcat)) {
        return false; 
    }

    //za pronalazenje id kategorije na osnovu njenog naziva
    $selectCategory = $conn->prepare("SELECT id_cat FROM categories WHERE name_cat = :pcat");
    $selectCategory->bindParam(":pcat", $pcat);
    $selectCategory->execute();

    $id_cat = $selectCategory->fetchColumn();

    if (!$id_cat) {
        return false; // ako kategorija ne postoji
    }

    //update 
    $sql = "UPDATE products SET name_prod = :name_prod, description = :desc, path = :path, price = :price, id_cat = :id_cat WHERE id_prod = :id";

    $update = $conn->prepare($sql);
    $update->bindParam(":name_prod", $pname);
    $update->bindParam(":desc", $pdesc);
    $update->bindParam(":path", $ppath);
    $update->bindParam(":price", $pprice);
    $update->bindParam(":id_cat", $id_cat);
    $update->bindParam(":id", $id);

    $result = $update->execute();
    return $result;
}

function deleteProduct($id) {
    global $conn;

    $sql = "DELETE FROM products WHERE id_prod = :id";
    $delete = $conn->prepare($sql);
    $delete->bindParam(":id", $id);
    $result = $delete->execute();

    return $result;
}


//dodavanje features u adminu
function addFeatureToProduct($productName, $featureName, $featureValue) {
    global $conn;

    // validacija polja
    $productName = htmlspecialchars(strip_tags($productName));
    $featureName = htmlspecialchars(strip_tags($featureName));
    $featureValue = htmlspecialchars(strip_tags($featureValue));

    if (empty($productName) || empty($featureName)) {
        return false; 
    }

    // select
    $productIdQuery = "SELECT id_prod FROM products WHERE name_prod = :product_name";
    $stmt = $conn->prepare($productIdQuery);
    $stmt->bindParam(':product_name', $productName);
    $stmt->execute();
    $productId = $stmt->fetchColumn();

    // ako proizvod nije pronadjen
    if (!$productId) {
        return false; 
    }

    // da li feature vec postoji za zadati proizvod
    $existingFeatureQuery = "SELECT f.id_feature
                             FROM features f
                             JOIN feature_item fi ON f.id_feature = fi.id_feature
                             JOIN products p ON fi.id_prod = p.id_prod
                             WHERE f.name_feat = :feature_name
                             AND f.value = :feature_value
                             AND p.id_prod = :product_id";
    $stmt = $conn->prepare($existingFeatureQuery);
    $stmt->bindParam(':feature_name', $featureName);
    $stmt->bindParam(':feature_value', $featureValue);
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();
    $existingFeatureId = $stmt->fetchColumn();

    // ako da, uzmi id
    // ako ne, insert nove vr.
    if (!$existingFeatureId) {
        // insert
        $insertFeatureQuery = "INSERT INTO features (name_feat, value) VALUES (:feature_name, :value)";
        $stmt = $conn->prepare($insertFeatureQuery);
        $stmt->bindParam(':feature_name', $featureName);
        $stmt->bindParam(':value', $featureValue);
        $stmt->execute();
        $existingFeatureId = $conn->lastInsertId();
    }

    // insert medjutabele
    $insertQuery = "INSERT INTO feature_item (id_prod, id_feature) VALUES (:id_prod, :id_feature)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bindParam(':id_prod', $productId);
    $stmt->bindParam(':id_feature', $existingFeatureId);
    $result = $stmt->execute();

    return $result;
}




//features_name
function addFeatureName($featureName) {
    global $conn;

    if (empty($featureName)) {
        return "Feature name cannot be empty.";
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $featureName)) {
        return "Feature name can only contain letters and spaces.";
    }

    $query = "SELECT * FROM features WHERE name_feat = :featureName";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':featureName', $featureName);
    $stmt->execute();
    $existingFeature = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingFeature) {
        return "Feature name already exists.";
    } else {
        $query = "INSERT INTO features (name_feat) VALUES (:featureName)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':featureName', $featureName);
        $stmt->execute();

        return "Feature name added successfully.";
    }
}


//za statistiku
function getLogData() {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/optics/config/data/log.txt';
    
    if (!file_exists($logFile)) {
        return [];
    }

    return file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}


function calculateStatistics($logEntries) {
    $pageAccessCount = [];
    $loggedInUsersToday = [];
    $today = date('Y-m-d');  

    //echo "datum: $today<br>";

    foreach ($logEntries as $entry) {
        // echo "$entry<br>";

        $explodedEntry = explode(';', $entry);

        if (count($explodedEntry) === 3) {
            list($user, $page, $logDate) = $explodedEntry;

            // echo "User: $user, Page: $page, Log Date: $logDate<br>";

            // brojac pristupa stranicama
            if (!isset($pageAccessCount[$page])) {
                $pageAccessCount[$page] = 0;
            }
            $pageAccessCount[$page]++;

            // da li je user logovan i na danasnji dan
            if ($user !== 'Guest' && $logDate === $today) {
                $loggedInUsersToday[$user] = true;  
            }
        } else {
            // echo "dobar format<br>";
        }
    }

    // ukupan pristup
    $totalAccesses = array_sum($pageAccessCount);
    $pageAccessPercentage = [];

    if ($totalAccesses > 0) {
        foreach ($pageAccessCount as $page => $count) {
            $pageAccessPercentage[$page] = ($count / $totalAccesses) * 100;
        }
    }

    // echo "count($loggedInUsersToday)";

    return [
        'pageAccessPercentage' => $pageAccessPercentage,
        'numLoggedInUsersToday' => count($loggedInUsersToday)
    ];
}






// function uploadImage($fileInputName) {
//     $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/path/to/your/image/directory/'; // Adjust the path
//     $targetFile = $targetDir . basename($_FILES[$fileInputName]['name']);
//     $uploadOk = 1;
//     $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

//     // Check if image file is a actual image
//     $check = getimagesize($_FILES[$fileInputName]['tmp_name']);
//     if ($check === false) {
//         return false; // Not an image
//     }

//     // Check file size (5MB maximum)
//     if ($_FILES[$fileInputName]['size'] > 5000000) {
//         return false; // File too large
//     }

//     // Allow certain file formats
//     if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
//         return false; // Only certain file formats allowed
//     }

//     // Check if $uploadOk is set to 0 by an error
//     if ($uploadOk == 0) {
//         return false;
//     } else {
//         if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
//             return '/path/to/your/image/directory/' . basename($_FILES[$fileInputName]['name']);
//         } else {
//             return false; // Upload failed
//         }
//     }
// }




?>