<?php

function calculateSubtotal($price, $quantity) {
    return $price * $quantity;
}

if (isUserLoggedIn() && isset($_SESSION['user']->id_user)) {

    if (userHasCart() && is_array($_SESSION['user']->cart)) {

// if(isset($_SESSION['user']) && isset($_SESSION['user']->id_user)) {

//     if(isset($_SESSION['user']->cart) && is_array($_SESSION['user']->cart)) {
        
?>

<main>
        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Cart</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-white">Cart</li>
            </ol>
        </div>
        <!-- Single Page Header End -->


        <!-- Cart Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Products</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($_SESSION['user']->cart as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="assets/<?= $item->path ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="<?=$item->name_prod?>">
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4"><?= $item->name_prod ?></p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4"><?= $item->price ?></p>
                                </td>
                                <td>
                                    <div class="input-group quantity mt-4" style="width: 120px;"> 
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center border-0" value="<?= isset($item->quantity) ? $item->quantity : 1 ?>">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4"><?= calculateSubtotal($item->price, isset($item->quantity) ? $item->quantity : 1) ?></p>
                                </td>
                                <td>
                                    <button class="btn btn-md rounded-circle bg-light border mt-4 remove-product-btn" data-product-id="<?=$item->id_prod?>">
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    
                    <button id="proceed-btn" class="btn btn-primary mt-4">Proceed</button>

                </div>
                
            </div>
        </div>

<!-- Cart Page End -->


</main>

<?php
    } else {
        echo `Your cart is empty.`;
        //console.log("Your cart is empty.");
    }
} else {
    echo "Please log in as a member to access your cart.";
    //console.log("Log in");
}
?>