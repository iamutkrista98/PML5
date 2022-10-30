<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./images/logo-no-tag-svg.svg">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/fonts.css">
  <link rel="stylesheet" href="./css/colors.css">
  <link rel="stylesheet" href="./css/myaccount.css">
  <title>My Account</title>
</head>

<body id="body" class="">
  <header>
    <?PHP
    include "./components/nav/nav.php";
    ?>
  </header>
  <!-- Header Ends -->
  <?PHP
  include "./components/searchbox/searchbox.php";
  include "./components/darkToggle/darkToggle.php";
  ?>
  <?php
  $eimage = '';
  $errorarray = array('');
  if (isset($_SESSION['USER_ID'])) {
    if (isset($_POST['update'])) {
      $id = $_POST['eid'];
      $ename = $_POST['ename'];
      $eaddress = $_POST['eaddress'];
      $eemail = $_POST['eemail'];
      $edob = $_POST['edob'];
      $econtact = $_POST['ephone'];
      $epass = trim($_POST['epass']);
      $passpattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])\S{6,}$/"; //regular expression to validate password pattern

      if (empty($epass)) //checking if password field is left empty
      {
        $filepath = "images/login-page/" . $_FILES["file"]["name"];
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) {
        } else {
        }



        $eimage = $_FILES['file']['name'];

        include('connection.php');
        if (!empty($eimage)) {
          $sql = oci_parse($conn, "UPDATE CUSTOMER SET CUSTOMER_NAME='$ename',ADDRESS='$eaddress',EMAIL_ADDRESS='$eemail',DOB='$edob',CONTACT_NO='$econtact',PROFILEIMAGE='$eimage' WHERE USER_ID='$id'");
        } else {
          $sql = oci_parse($conn, "UPDATE CUSTOMER SET CUSTOMER_NAME='$ename',ADDRESS='$eaddress',EMAIL_ADDRESS='$eemail',DOB='$edob',CONTACT_NO='$econtact' WHERE USER_ID='$id'");
        }
        if (oci_execute($sql)) {
          echo "<script>alert('Details updated successfully')</script>";
        }
      } else {
        if (!preg_match($passpattern, $epass)) //validation of password 
        {
          $errorarray[0] = " ( Password should contain at least 1 digit,1 uppercase and 1 lowercase character )";
          echo "<script>alert('Password should contain at least 1 digit,1 uppercase and 1 lowercase character')</script>";
        } else {
          $epass = md5($epass);
          $sql = oci_parse($conn, "UPDATE CUSTOMER SET CUSTOMER_NAME='$ename',ADDRESS='$eaddress',EMAIL_ADDRESS='$eemail',DOB='$edob',CONTACT_NO='$econtact',PASSWORD='$epass' WHERE USER_ID='$id'");
          if (oci_execute($sql)) {
            echo "<script>alert('Password changed successfully')</script>";
          } else {
          }
        }
      }
    }
  } else {
    header('Location:login.php');
  }










  ?>
  <!-- Dark Toggle Ends -->
  <div class='account'>
    <h1 class='accheading'>My Account Page</h1>
    <h3 align="center"> <?php if (isset($_SESSION['USER_NAME'])) echo $_SESSION['USER_NAME'];
                        else if (isset($_SESSION['TRADER_NAME'])) echo $_SESSION['TRADER_NAME']; ?></h3>
    <div class='details'>
      <?php
      include('connection.php');
      if (isset($_SESSION['USER_ID'])) {
        $sql = oci_parse($conn, 'SELECT * FROM CUSTOMER');
        oci_execute($sql);
        while ($row = oci_fetch_array($sql, OCI_ASSOC + OCI_RETURN_NULLS)) {
          if (oci_result($sql, 'USER_ID') == $_SESSION['USER_ID']) {
            echo '<form method="post" action="" enctype="multipart/form-data">';
            $pimg = $row['PROFILEIMAGE'];
            echo "<img src='images/login-page/$pimg' class='pimage'>";
            echo "<label>" . "Customer Registered Since: " . "$row[REGISTRATION_DATE]" . "</label><br>";
            echo '<label>Profile Image</label>';
            echo "<input type='file' name='file' placeholder='update profile';>";
            echo "<input type='hidden' name='eid' value='$row[USER_ID]' readonly>";
            echo '<label>Full Name</label> ';
            echo "<input type='text' name='ename' value='$row[CUSTOMER_NAME]'>";
            echo '<label>Address</label>';
            echo "<input type='text' name='eaddress' value='$row[ADDRESS]'>";
            echo '<label>Email Address</label>';
            echo "<input type='text' name='eemail' value='$row[EMAIL_ADDRESS]' readonly>";
            echo '<label>Date Of Birth</label>';
            echo "<input type='text' name='edob' value='$row[DOB]'>";
            echo '<label>Contact Number</label>';
            echo "<input type='text' name='ephone' value='$row[CONTACT_NO]'>";
            echo '<label>Change Password?</label>';
            echo "<input type='password' name='epass' value=''>";
            echo "<button class='update' type='submit' name='update'><i class='bx bxs-user-check' ></i>Update</button>";
            echo "</form>";
            echo "<div class='options'>";
            echo "<a href='wishlist.php' class='wishoption'><i class='bx bx-heart-circle' ></i>Wishlist</a>";
            echo "<a href='viewcustomerorders.php' class='orderoption'><i class='bx bx-package' ></i>Orders</a>";

            echo "</div>";
          }
        }
      }

      ?>
    </div>
  </div>
  <footer>
    <?PHP
    include "./components/footer/footer.php";
    ?>
  </footer>
  <!-- Footer Ends -->
  <script src="./js/main.js"></script>
  <script src="./js/hero.js"></script>
</body>

</html>