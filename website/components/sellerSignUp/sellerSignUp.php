<?php include('./sendmail.php');
include('./function.php');
?>
<?php
include('smtp/PHPMailerAutoload.php');
if (isset($_POST['tregister'])) {
  $tname = trim($_POST['tname']);
  $temail = trim($_POST['temail']);
  $sname = trim($_POST['sname']);
  $saddress = trim($_POST['saddress']);
  $tphone = trim($_POST['phone']);
  $tcat = trim($_POST['category']);
  $rusername = generate_username();
  $rpassword = generate_password();
  $aemail = 'managementdbq@gmail.com';

  include('./connection.php');
  $req = oci_parse($conn, "INSERT INTO TRADER_REQUEST(TRADER_NAME,TRADER_EMAIL,SHOP_NAME,SHOP_ADDRESS,PRODUCT_CATEGORY,CONTACT_NO,STATUS,GENERATED_USERNAME,GENERATED_PASSWORD) VALUES('$tname','$temail','$sname','$saddress','$tcat','$tphone',0,'$rusername','$rpassword')");
  oci_execute($req);
  $html = "<head><style>.mail{text-align:justify;}</style></head><body><div class='mail'>Dear Trader,<br>Thank You For Registering at The Daily Bread Quintessential (DBQ MART). An admin or someone from management will get back to you as soon as possible after verifying the details you submitted.
  The details you submitted are as follows:<br>
      <h3>Trader Name: $tname</h3><h3>Email Address: $temail</h3><h3>Shop Name: $sname</h3><h3>Product Category: $tcat</h3><h3>Shop Address: $saddress</h3><h3>Contact Number: $tphone</h3><br>THANKS, DBQ MART TEAM</div></body>";
  send_email($temail, $html, 'Registration Under Review');
  $getdetails = oci_parse($conn, "SELECT * FROM TRADER_REQUEST WHERE TRADER_NAME='$tname' AND TRADER_EMAIL='$temail'");
  oci_execute($getdetails);
  while ($fetchdetails = oci_fetch_assoc($getdetails)) {
    $genname = $fetchdetails['GENERATED_USERNAME'];
    $genpassword = $fetchdetails['GENERATED_PASSWORD'];
  }


  $html1 = authenticateTrader($tname, $temail, $sname, $tcat, $saddress, $tphone, $genname, $genpassword);
  send_email($aemail, $html1, 'A Trader Interested in Registering');
  echo "<script>alert('Thank you for showing an interest to register as a trader! An email has been sent to the respective email address.')</script>";
  echo "<script>window.location = './index.php'</script>";
} else {
}


?>









<section id="seller-login">
  <div class="container">
    <div class="seller-login-elements">
      <div class="seller-login-picture">
        <img src="./images/login-page/seller-form-image.png" alt="" id="seller-login-img">
      </div>
      <div class="seller-login-form">
        <div class="seller-login-form-text">
          <h1>Want to be a Seller?</h1>
          <h3>Fill the form below!</h3>
        </div>
        <form action="" method='post'>
          <div class="seller-login-form-element">
            <label for="Trader Name">Trader Name</label>
            <br>
            <input type="text" name="tname" required>
          </div>
          <br>
          <div class="seller-login-form-element">
            <label for="email">Email Address</label>
            <br>
            <input type="email" name="temail" required>
          </div>
          <br>
          <div class="seller-login-form-element">
            <label for="shopname">Shop Name</label>
            <br>
            <input type="text" name="sname" required>
          </div>
          <br>
          <div class="seller-login-form-element">
            <label for="address">Shop Address</label>
            <br>
            <input type="text" name="saddress" required>
          </div>
          <br>
          <div class="seller-login-form-element">
            <label for="address">Product Category</label>
            <br>
            <input type="text" name="category" required>
          </div>
          <br>
          <div class="seller-login-form-element">
            <label for="address">Contact Number</label>
            <br>
            <input type="text" name="phone" required>
          </div>
          <br>
          <button type="Submit" name='tregister'>Submit</button>
        </form>
        <span>Already A Trader? <a href="./login.php?loginpoint=sellerLogin"> Login </a></span>
      </div>
    </div>
  </div>
</section>