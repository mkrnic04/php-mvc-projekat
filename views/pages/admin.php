<?php
    ob_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';

    if (!isset($_SESSION['user']) || $_SESSION['user']->Role !== 'admin') {
      header('Location: index.php?page=home'); 
      exit();
    }


    $products = getProductsAndCategories();

    $features = getAllFeatures();

    $distinctFeatureNames = getAllDistinctFeatureNames();

    //$orders = getOrders();


    //za statistiku
    $logData = getLogData();
    $stats = calculateStatistics($logData);



    //orders
    $query = "SELECT o.id_ord, o.date, u.first_name, u.last_name, u.email, p.name_prod AS product_name, od.quantity 
                FROM orders o 
                LEFT JOIN users u ON o.id_user = u.id_user
                LEFT JOIN order_details od ON o.id_ord = od.id_ord
                LEFT JOIN products p ON od.id_prod = p.id_prod";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_OBJ); 


    ob_end_flush();
?>

<main>
    <div class="container-scroller">
      <!-- partial:../../partials/_navbar.html -->
      
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_sidebar.html -->
        
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Tables </h3>
            </div>
            <div class="row">
              
                
            <!-- products -->
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Products</h4>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                <th>Product name</th>
                                <th>Description</th>
                                <th>Path</th>
                                <th>Price</th>
                                <th>Category name</th>
                                <th>Modify</th>
                                <th>Remove</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach($products as $prod): ?>

                                <tr>
                                <td><?=$prod->name_prod?></td>
                                <td class="desc"><?=$prod->description?></td>
                                <td><?=$prod->path?></td>
                                <td><?=$prod->price?></td>
                                <td><?=$prod->name_cat?></td>
                                <td>
                                    
                                    <button class="btn-edit" data-id="<?=$prod->id_prod?>">Edit</button>
                                    <input type="hidden" name="id_prod" id="id_prod" value=""/>
                                </td>
                                <td>
                                    <button class="btn-delete" data-id="<?=$prod->id_prod?>">Delete</button>
                                </td>
                                </tr>
                            
                                <?php endforeach; ?>
                            
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>


            <h4 class="card-title">Unos/edit proizvoda</h4>                       
            <form id="edit-form" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="form">
            
                <div class="column">
                    <div class="input-box">
                        <label>Product Name</label>
                        <input type="text" name="pname" id="pname" />
                    </div>

                    <div class="input-box">
                        <label>Description</label>
                        <input type="text" name="pdesc" id="pdesc" />
                    </div>

                    <div class="input-box">
                        <label>Path</label>
                        <input type="text" name="ppath" id="ppath" />
                    </div>

                    <div class="input-box">
                        <label>Price</label>
                        <input type="text" name="pprice" id="pprice" />
                    </div>

                    <div class="input-box">
                        <label>Category Name</label>
                        <input type="text" name="pcat"  id="pcat" />
                    </div>

                    <button type="submit" class="border-radius" name="btnInsert" id="btnInsert">Insert</button>
                    <button type="submit" class="border-radius" name="btnUpdate" id="btnUpdate" disabled>Update</button>
                    
                </div>
            </form>        



            <!-- features -->
            <div class="col-lg-12 grid-margin stretch-card mt-5">
                <div class="card">
                    <div class="card-body">
              
                        <h4 class="card-title mt-4">Features</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Feature Name</th>
                                    <th>Feature Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($features as $feature): ?>
                                    <tr>
                                        <td><?= $feature->product_name ?></td>
                                        <td><?= $feature->name_feat ?></td>
                                        <td><?= $feature->value ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


            
            <form id="add-feature-form" action="#" method="POST" class="form">
    <div class="row">
        <div class="col-md-4">
            <div class="input-box">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control" />
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-box">
                <label for="feature_name">Feature Name</label>
                <select name="feature_name" id="feature_name" class="form-select">
                    <?php foreach ($distinctFeatureNames as $featureName): ?>
                        <option value="<?= $featureName ?>"><?= $featureName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-box">
                <label for="feature_value">Feature Value</label>
                <input type="text" name="feature_value" id="feature_value" class="form-control" />
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary" name="btnAddFeature" id="btnAddFeature">Add Feature</button>
    </div>
</form>



            

            <!-- features_name -->
            <form id="add-feature-name-form" action="models/add_feature_name.php" method="POST" class="form mb-5 pb-5">
                <div class="column w-50">
                    <div class="input-box">
                        <label>Feature Name</label>
                        <input type="text" name="feature_name" id="feature_name" class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary" name="btnAddFeatureName" id="btnAddFeatureName">Add Feature Name</button>
                </div>
            </form>

        

              <!-- orders -->
              <div class="container mt-5 pt-5">
                  <h2>Orders</h2>
                  <div class="table-responsive">
                      <table class="table table-bordered bg-white">
                          <thead>
                              <tr class="bg-warning">
                                  <th>Order ID</th>
                                  <th>User</th>
                                  <th>Email</th>
                                  <th>Order Date</th>
                                  <th>Product Name</th>
                                  <th>Quantity</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php foreach ($orders as $order) : ?>
                                  <tr>
                                      <td><?= $order->id_ord ?></td>
                                      <td><?= $order->first_name . ' ' . $order->last_name ?></td>
                                      <td><?= $order->email ?></td>
                                      <td><?= $order->date ?></td>
                                      <td><?= $order->product_name ?></td>
                                      <td><?= $order->quantity ?></td>
                                  </tr>
                              <?php endforeach; ?>
                          </tbody>
                      </table>
                  </div>
              </div>

              <?php
                // prikaz po stranicama
                echo "<h3>Page Access Statistics</h3>";
                foreach ($stats['pageAccessPercentage'] as $page => $percentage) {
                    echo "<p>{$page}: " . number_format($percentage, 2) . "%</p>";
                }

                // prikaz ulogovanih korisnika u danu
                echo "<h3>Users Logged In Today</h3>";
                echo "<p>{$stats['numLoggedInUsersToday']} users have logged in today.</p>";
            ?>

        </div>
    </div>
</main>









