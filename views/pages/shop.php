<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';

// kategorije sa brojem proizvoda
$categoriesWithProductCount = getCategoriesWithProductCount();

$productsPerPage = 8;

$sortOption = 'default';

?>

<main>
    <!-- Fruits Shop Start -->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Fresh fruits shop</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-xl-3">
                            <div class="input-group w-100 mx-auto d-flex">
                                <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                                <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <div class="col-6"></div>
                        <div class="col-xl-3">
                        <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                            <label for="sort">Sort By:</label>
                            <select id="sort" name="sort" class="border-0 form-select-sm bg-light me-3" form="sortform">
                                <option value="default">Default</option>
                                <option value="price_low_to_high">Price: Low to High</option>
                                <option value="price_high_to_low">Price: High to Low</option>
                            </select>
                        </div>

                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <h4>Categories</h4>
                                        <ul class="list-unstyled fruite-categorie">
                                            <?php foreach($categoriesWithProductCount as $category): ?>
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <a href="#"><i class="fas fa-apple-alt me-2"></i><?= $category->name_cat ?></a>
                                                        <span>(<?= $category->product_count ?>)</span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                        <div id="products-container" class="row g-4 justify-content-center">
                                    <!-- proizvodi -->
                                </div>
                                <!-- Pagination -->
                                <div id="pagination-container" class="pagination d-flex justify-content-center mt-5">
                                    <!-- paginacija -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fruits Shop End -->
</main>
