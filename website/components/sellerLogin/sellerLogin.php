<?php
$error = '';
$username = '';
$password = '';
if (isset($_POST['login'])) {
  include('connection.php');
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $password = $password;
  $qry = oci_parse($conn, "SELECT * FROM TRADER WHERE USERNAME='$username' AND PASSWORD='$password'");
  oci_execute($qry);
  $check = oci_fetch($qry);
  oci_execute($qry);
  if ($check > 0) {
    $row = oci_fetch_assoc($qry);
    $status = $row['STATUS'];
    if ($status == 1) {
      $_SESSION['TRADER_ID'] = $row['USER_ID'];
      $_SESSION['TRADER_NAME'] = $row['USERNAME'];
      $name = $_SESSION['TRADER_NAME'];
      echo "<script>alert('Welcome Back $name')</script>";
      echo "<script>window.location = './trader.php?option=edit_details'</script>";
    } else {
      $error = '* Your account has been flagged as inactive by administration!';
    }
  } else {
    $error = '* Please enter valid username or password';
  }
}
?>
<section id="sellerLogin">
  <div class="container">
    <div class="seller-le">
      <div class="seller-l-img">
        <img src="./images/login-page/Hello There.png" alt="">
      </div>
      <div class="seller-l-form">
        <div class="seller-l-form-text">
          <h1>Hello Seller</h1>
          <h3>We're Glad you're here</h3>
        </div>
        <form action="" method='post'>
          <div class="seller-l-form-element">
            <label for="Name">Your Username</label>
            <br>
            <input type="text" name="username" required>
          </div>
          <br>
          <div class="seller-l-form-element">
            <label for="password">Your Password</label>
            <br>
            <input type="password" name="password" required>
          </div>
          <br>
          <?php echo "<h6>" . $error . "</h6>"; ?>
          <button type="Submit" name='login'>Login</button>
        </form>
        <span>Not a Seller? <a href="./login.php?loginpoint=registerseller"> Register Now </a></span>
      </div>
    </div>
  </div>
</section>