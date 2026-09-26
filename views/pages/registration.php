<main>
  <div class="container-fluid py-5 pos bg-light">
    <div class="container py-5">
      <div class="row g-4 d-flex justify-content-center">
        <div class="col-lg-7">
          <h2 class="text-center mb-5">Registration Form</h2>
          <form action="./models/registration_logic.php" method="POST">
            <div class="input-group">
              <input type="text" class="form-control border-0 py-3 mb-4 me-2" placeholder="First name" name="fname" value="<?= isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : '' ?>" />
              <input type="text" class="form-control border-0 py-3 mb-4" placeholder="Last name" name="lname" value="<?= isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : '' ?>" />
            </div>

            <div class="input-group">
              <input type="password" class="form-control border-0 py-3 mb-4 me-2" placeholder="Password" name="pass" />
              <input type="password" class="form-control border-0 py-3 mb-4" placeholder="Confirm password" name="passConf" />
            </div>

            <div class="input-group">
              <input type="text" class="form-control border-0 py-3 mb-4 me-2" placeholder="Email address: name@example.com" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" />
              <input type="text" class="form-control border-0 py-3 mb-4" placeholder="Phone Format: 060/1234567" name="phone" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" />
            </div>

            <div class="input-group">
              <input type="text" class="form-control border-0 py-3 mb-4 me-2" placeholder="Country" name="country" value="<?= isset($_POST['country']) ? htmlspecialchars($_POST['country']) : '' ?>" />
              <input type="text" class="form-control border-0 py-3 mb-4" placeholder="City" name="city" value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '' ?>" />
            </div>

            <div class="input-group">
              <input type="text" class="form-control border-0 py-3 mb-4 me-2" placeholder="Enter street address" name="address" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>" />
              <input type="text" class="form-control border-0 py-3 mb-4" placeholder="Enter postal code" name="postal" value="<?= isset($_POST['postal']) ? htmlspecialchars($_POST['postal']) : '' ?>" />
            </div>

            <div class="text-center">
              <button class="btn form-control border-secondary py-3 bg-white text-primary" type="submit" name="btnReg">Register</button>
            </div>
          </form>

          <?php 
            $error = getFlash('error'); 
            if ($error): 
          ?>
              <div class="alert alert-danger text-center w-75 mx-auto mt-4"><?= $error ?></div>
          <?php 
            endif;
          ?>

          <?php 
            $success = getFlash('success'); 
            if ($success): 
          ?>
              <div class="alert alert-success text-center w-75 mx-auto mt-4"><?= $success ?></div>
          <?php 
            endif;
          ?>

        </div>
      </div>
    </div>
  </div>
</main>