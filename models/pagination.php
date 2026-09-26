<?php
header('Content-Type: application/json');

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';

$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'default';
switch ($sortOption) {
    case 'price_low_to_high':
        $orderBy = 'ORDER BY price ASC';
        break;
    case 'price_high_to_low':
        $orderBy = 'ORDER BY price DESC';
        break;
    default:
        $orderBy = 'ORDER BY id_prod DESC';
        break;
}

// broj proizvoda po stranici
$productsPerPage = 8;

// trenutna stranica
$paginationPage = isset($_GET['pagination']) && is_numeric($_GET['pagination']) && $_GET['pagination'] > 0 ? (int)$_GET['pagination'] : 1;
$paginationPage = max($paginationPage, 1); // bar jedna

$startIndex = ($paginationPage - 1) * $productsPerPage;

$sql = "SELECT * FROM products p JOIN categories c ON p.id_cat = c.id_cat $orderBy LIMIT :startIndex, :productsPerPage";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
$stmt->bindParam(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ukupno proizvoda
$totalQuery = "SELECT COUNT(*) AS total FROM products";
$totalResult = $conn->query($totalQuery)->fetch(PDO::FETCH_OBJ);
$totalProducts = (int)$totalResult->total;

// json
$data = [
    'products' => $products,
    'totalProducts' => $totalProducts,
    'productsPerPage' => $productsPerPage,
    'paginationPage' => $paginationPage
];

echo json_encode($data);
?>
