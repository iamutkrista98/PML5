<?php include('./sendmail.php');
?>
<?php
include('smtp/PHPMailerAutoload.php');
require('./function.php');
$email = '';
$username = '';
$dob = '';
$address = '';
$password = '';
$cname = '';
$phone = '';
$errorarray = array('', '', '', '', '', '', '');
$check = '';
$uniqueerror = '';
if (isset($_POST['registercustomer'])) {
  //capturing the values filled within the form
  $cname = trim($_POST['cname']);
  $email = trim($_POST['email']);
  $username = trim($_POST['uname']);
  $dob = trim($_POST['dob']);
  $address = trim($_POST['address']);
  $phone = trim($_POST['phone']);
  $password = trim($_POST['password']);
  $passpattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])\S{6,}$/"; //regular expression to validate password pattern
  $usernamepatt = "/^([a-zA-Z]+)$/";
  $errorcount = 0;

  include('./connection.php');
  $res = oci_parse($conn, "SELECT * FROM CUSTOMER WHERE EMAIL_ADDRESS='$email' OR USERNAME='$username'");
  oci_execute($res);
  $check = oci_fetch($res);
  if ($check > 0) {
    $uniqueerror = '*Email Address or Username already exists on our database';
    $errorcount++;
  }

  if (empty($cname)) //checking if username field is left empty
  {

    $errorarray[0] = "* Name is required";
    $errorcount++;
  }
  if (empty($email)) //checking if email field is left empty
  {
    $errorarray[1] = "* Email Address is required";
    $errorcount++;
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //validation of email format using filter validate
      $errorarray[1] = "* Invalid email format";
      $errorcount++;
    }
  }
  if (empty($username)) //checking if username field is left empty
  {

    $errorarray[2] = "* Username is required";
    $errorcount++;
  } else {
    if (strlen($username) < 6) //checking if username is less than 6 characters
    {

      $errorarray[2] = "* Username must be at least 6 characters";
      $errorcount++;
    } else {
      if (!preg_match($usernamepatt, $username)) {
        $errorarray[2] = "* Username must be alphabets only";
        $errorcount++;
      }
    }
  }
  if (empty($dob)) //checking if username field is left empty
  {

    $errorarray[3] = "* Date of Birth is required";
    $errorcount++;
  }
  if (empty($address)) //checking if username field is left empty
  {

    $errorarray[4] = "* Address is required";
    $errorcount++;
  }
  if (empty($phone)) //checking if username field is left empty
  {

    $errorarray[5] = "* Contact No is required";
    $errorcount++;
  }

  if (empty($password)) //checking if password field is left empty
  {
    $errorarray[6] = "* Password is required";
    $errorcount++;
  } else {
    if (!preg_match($passpattern, $password)) //validation of password 
    {
      $errorarray[6] = " ( Password should contain at least 1 digit,1 uppercase and 1 lowercase character )";
      $errorcount++;
    }
  }

  if ($errorcount == 0) {
    include('connection.php');
    $npassword = $password;
    $password = md5($password);
    $regd_on = date('j:M:y H:i:s');
    $token = generate_token();
    $sql = oci_parse($conn, "INSERT INTO CUSTOMER(CUSTOMER_NAME,USERNAME,PASSWORD,STATUS,ADDRESS,EMAIL_ADDRESS,DOB,CONTACT_NO,REGISTRATION_DATE,EMAIL_VERIFY,TOKEN,PROFILEIMAGE) VALUES('$cname','$username','$password','1','$address','$email','$dob','$phone',to_date('$regd_on', 
    'DD:MON:YY HH24:MI:SS'),'0','$token','profile.png')");
    if (oci_execute($sql)) {
      $html = getVerifyMail($cname, $username, $email, $npassword, $address, $token);
      //$html = "<head><style>.mail{text-align:justify;font-family: 'Nunito Sans', Helvetica, Arial, sans-serif;font-size:16px;}button{border:none;background-color:#F2B34E;padding:10px;font-size:24px; border-radius:12px;}a{text-decoration: none;color:black}</style></head><body><div class='mail'><b>Dear $cname,</b><br>Thank you for registering at The Daily Bread Quintessential (DBQ MART)! A great variety of fresh local produce are available at your disposal<h2>Please click on this button for verification:  " . "<button class='verify'><a href='http://localhost/dbq2/website/verify.php?email=$email&token=$token'>Verify</a></button></h2>" . "<br>THANKS, DBQ MART TEAM</div></body>";
      send_email($email, $html, 'Registration Successful, Verification Required');

      echo "<script>alert('You Have Successfully Registered! An email has also been sent to the registered email address for verification purposes before logging in')</script>";
      echo "<script>window.location = './login.php?loginpoint=customerLogin'</script>";
    } else {
    }
  }
}


?>






<section id="customer-login">
  <div class="container">
    <div class="customer-login-elements">
      <div class="customer-login-picture">
        <img src="./images/login-page/customer-register.png" alt="" id="customer-login-img">
      </div>
      <div class="customer-login-form">
        <div class="customer-login-form-text">
          <h1>Welcome to DBQ MART!</h1>
          <h3>Fill the form below to register as customer</h3>
        </div>
        <form action="" method='post'>
          <div class="customer-login-form-element">
            <label for="Customer Name">Customer Name</label>
            <br>
            <input type="text" name="cname" placeholder='Your Name' value='<?php echo $cname; ?>'>
            <?php echo "<h6>" . $errorarray[0] . "</h6>"; ?>
          </div>
          <br>
          <div class="customer-login-form-element">
            <label for="Customer Email">Email Address</label>
            <br>
            <input type="email" placeholder='Your Email' name="email" value='<?php echo $email; ?>'><?php echo "<h6>" . $errorarray[1] . "</h6>"; ?>
          </div>
          <br>
          <div class="customer-login-form-element">
            <label for="Customer Username">Username</label>
            <br>
            <input type="text" name="uname" placeholder='Username' value='<?php echo $username; ?>'><?php echo "<h6>" . $errorarray[2] . "</h6>"; ?>
          </div>
          <br>
          <div class="customer-login-form-element">
            <label for="Customer DOB">Date of Birth</label>
            <br>
            <input type="text" name="dob" placeholder='DD/MM/YYYY' value='<?php echo $dob; ?>'><?php echo "<h6>" . $errorarray[3] . "</h6>"; ?>
          </div>
          <br>
          <div class="customer-login-form-element">
            <label for="email">Address</label>
            <br>
            <input type="text" name="address" placeholder='Address' value='<?php echo $address; ?>'><?php echo "<h6>" . $errorarray[4] . "</h6>"; ?>
          </div>
          <br>
          <div class="customer-login-form-element">
            <label for="contact">Contact Number</label>
            <br>
            <input type="text" name="phone" placeholder='Contact No' value='<?php echo $phone; ?>'><?php echo "<h6>" . $errorarray[5] . "</h6>"; ?>
          </div>
          <br>
          <div class="customer-login-form-element last-customer-form-element">
            <label for="password">Password</label>
            <br>
            <input type="password" name="password" placeholder='Password' value=''><?php echo "<h6>" . $errorarray[6] . "</h6>"; ?>
          </div>
          <br>
          <?php echo "<h6>" . $uniqueerror . "</h6>"; ?>
          <button type="Submit" name='registercustomer'>Register</button>
        </form>
        <span>Already Registered As Customer? <a href="./login.php?loginpoint=customerLogin"> Login Now </a></span>
      </div>
    </div>
  </div>
</section>