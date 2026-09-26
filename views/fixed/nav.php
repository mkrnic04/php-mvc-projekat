<?php

    function getAllNavItems() {
        global $conn;
        try {
    
            $query = "SELECT * FROM nav WHERE path IN ('index.php', 'shop.php', 'contact.php', 'author.php') OR id_role IS NULL";
            $stmt = $conn->query($query);
            $navItems = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            return $navItems;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return array(); 
        }
    }
    
    $navItems = getAllNavItems();

?>

<nav class="navbar navbar-light bg-white navbar-expand-xl">
    <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">Fruitables</h1></a>
    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars text-primary"></span>
    </button>
    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
        <div class="navbar-nav mx-auto">

            <?php foreach($navItems as $n):?>

            <a href="index.php?page=<?=strtolower($n->name)?>" class="nav-item nav-link active"><?=$n->name?></a>

            <?php endforeach; ?>

            <?php if(hasFlash("user")):?>
                <?php if($_SESSION['user']->Role == 'admin'):?>
                    <a href="index.php?page=admin" class="nav-item nav-link">Admin</a>
                    <a href="./models/logout.php" class="nav-item nav-link">Logout</a>
                <?php elseif($_SESSION['user']->Role == 'member'):?>
                    <a href="./models/logout.php" class="nav-item nav-link">Logout</a>
                <?php endif; ?>
            <?php endif; ?>

            

        </div>
        <div class="d-flex m-3 me-0">
            <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
            <a href="index.php?page=cart" class="position-relative me-4 my-auto">
                <i class="fa fa-shopping-bag fa-2x"></i>
                <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
            </a>
            <!-- <a href="index.php?page=login" class="my-auto">
                <i class="fas fa-user fa-2x"></i>
            </a> -->

            <?php if(hasFlash("user")): ?>
                <a href="#" class="nav-item nav-link active">Logged in</a>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-item nav-link"><i class="fas fa-user-circle fa-2x me-2"></i>Login</a>
                <a href="index.php?page=registration" class="nav-item nav-link"><i class="far fa-user-circle fa-2x me-2"></i>Register</a>
            <?php endif; ?>

        </div>
    </div>
</nav>