<?php
include("session.php")
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>OCP</title>
  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap'>
<link rel="stylesheet" href="../style/login.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="icon" href="../icons/logo-ocp.jpg" type="image/y-icon">
</head>
<body>
<form action="" method="post">
<div class="wrapper">
  <div class="login_box">
    <div class="login-header">
      <span>LOGIN</span>
    </div>
    <?php
      if(!empty($errorMesage)){
            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>$errorMesage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>
            </button>
            </div>";
      }
      ?>
    <div class="logo">
      <img src="../icons/logo-ocp.jpg" alt="ocp">
    </div>

    <div class="input_box">
      <span><?php $errorMesage; ?>
      </span>
      <input value="<?php echo "$emailValue" ?>" type="text" id="user" class="input-field" name="email" required>
      <label for="user" class="label">Email</label>
      <i class="bx bx-user icon"></i>
    </div>
    <div class="input_box">
      <input type="password" id="pass" class="input-field" name="password" required>
      <label for="pass" class="label">Password</label>
      <i class="bx bx-lock-alt icon"></i>
    </div>
    <div class="input_box">
      <button class="input-submit" name="submit">Log in</button>
    </div>
  </div>
</div>
</form>
</body>
</html>