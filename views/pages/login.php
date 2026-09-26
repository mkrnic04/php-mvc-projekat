<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include $_SERVER['DOCUMENT_ROOT'] . '/optics/config/conn.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = post('email');
    $pass = md5(post('pass'));

    $query = "SELECT *, r.name_role as Role FROM users u JOIN roles r ON u.id_role = r.id_role WHERE email = :email";
    $result = $conn->prepare($query);
    $result->bindParam(":email", $email);
    $result->execute();

    $user = $result->fetch(PDO::FETCH_OBJ);

    if (!$user || $user->password != $pass) {
        setFlash('error', 'Wrong username or password.');
        header("Location: ../../index.php?page=login");
        exit;
    } else {
        unset($user->Password);
        $_SESSION["user"] = $user;  

        //CART
        // da li user ima cart u sesiji
        if(isset($_SESSION['user']->cart) && is_array($_SESSION['user']->cart)) {
          $existing_cart = isset($_SESSION['user']->cart) ? $_SESSION['user']->cart : [];
          // spajanje carta sa cartom sesije
          $_SESSION['user']->cart = array_merge($existing_cart, $_SESSION['user']->cart);
        } else {
          //setuj cart
          $_SESSION['user']->cart = [];
        }


        if ($user->Role == "member") {
            header("Location: ../../index.php?page=shop");
            exit;
        } 

        if ($user->Role == "admin") {
          header("Location: ../../index.php?page=admin");
          exit;
        } 
        
    }
}
?>

<main>
  <div class="container-fluid py-5 pos bg-light">
    <div class="container py-5">
      <div class="row g-4 d-flex justify-content-center">
        <div class="col-lg-7">
          <h2 class="text-center mb-5">Login Form</h2>
          <form action="./views/pages/login.php" method="POST">
              <input type="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="Email address" name="email" />
              <input type="password" class="w-100 form-control border-0 py-3 mb-4" placeholder="Password" name="pass" />
              <div class="text-center">
              <button class="w-25 btn btn-log form-control border-secondary py-3 bg-white text-primary" type="submit">Login</button>
              </div>
          </form>
        </div>

        <?php 
            $error = getFlash('error'); 
            if ($error): 
        ?>
            <div class="alert alert-danger w-50" style="color: red; text-align: center;"><?= $error ?></div>
        <?php 
            endif;
        ?>

      </div>
    </div>
  </div>
</main>
