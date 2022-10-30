<?php
$error = '';
$username = '';
$password = '';
if (isset($_POST['login'])) {
  include('connection.php');
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $password = md5($password);
  $qry = oci_parse($conn, "SELECT * FROM CUSTOMER WHERE USERNAME='$username' AND PASSWORD='$password'");
  oci_execute($qry);
  $check = oci_fetch($qry);
  oci_execute($qry);
  if ($check > 0) {
    $row = oci_fetch_assoc($qry);
    $status = $row['STATUS'];
    $emailverify=$row['EMAIL_VERIFY'];
    if ($status == 1 && $emailverify==1) {
      $_SESSION['USER_ID'] = $row['USER_ID'];
      $_SESSION['USER_NAME'] = $row['USERNAME'];
      $name = $_SESSION['USER_NAME'];
      echo "<script>alert('Logged In Successfully! Welcome back $name')</script>";
      echo "<script>window.location = './index.php'</script>";
    } else {
      $error = '* Your account has been flagged as inactive by administration or not verified!';
    }
  } else {
    $error = '* Please enter valid username or password';
  }
}
?>




<section id="sellerLogin">
  <div class="container">
    <div class="customer-le">
      <div class="customer-l-img">
        <img src="./images/login-page/customer-login.png" alt="">
      </div>
      <div class="customer-l-form">
        <div class="customer-l-form-text">
          <h1>Hello Dear</h1>
          <h3>We're Glad you're here</h3>
        </div>
        <form action="" method='post'>
          <div class="customer-l-form-element">
            <label for="Name">Your Username</label>
            <br>
            <input type="text" name="username" value='<?php echo $username; ?>' required>
          </div>
          <br>
          <div class="customer-l-form-element">
            <label for="password">Your Password</label>
            <br>
            <input type="password" name="password" required>
          </div>
          <?php echo "<h6>" . $error . "</h6>"; ?>
          <button type="Submit" name='login'>Login</button>
        </form>
        <span>Not a Customer? <a href="./login.php?loginpoint=registercustomer"> Register Now </a></span>
      </div>
    </div>
  </div>
</section>