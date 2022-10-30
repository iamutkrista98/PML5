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
  <link rel="stylesheet" href="./components/loginFirst/loginFirst.css">
  <?PHP
  if ($_GET['loginpoint'] == "sellerLogin") {
  ?>
    <link rel="stylesheet" href="./components/sellerLogin/sellerLogin.css">
  <?PHP
  } else if ($_GET['loginpoint'] == "registerseller") {
  ?>
    <link rel="stylesheet" href="./components/sellerSignUp/sellerSignUp.css">
  <?PHP
  }
  ?>
  <?PHP
  if ($_GET['loginpoint'] == "customerLogin") {
  ?>
    <link rel="stylesheet" href="./components/customerLogin/customerLogin.css">
  <?PHP
  } else if ($_GET['loginpoint'] == "registercustomer") {
  ?>
    <link rel="stylesheet" href="./components/customerSignUp/customerSignUp.css">
  <?PHP
  }
  ?>
  <title>DBQ Mart - Account</title>
</head>

<body id="body">
  <header>
    <?PHP
    include "./components/nav/nav.php";
    ?>
  </header>
  <!-- Header Ends -->
  <?PHP
  include "./components/darkToggle/darkToggle.php";
  ?>
  <!-- Dark Toggle Ends -->
  <?PHP
  include "./components/searchbox/searchbox.php";
  ?>
  <!-- Search Box Ends -->
  <?PHP
  if ($_GET['loginpoint'] == "mainpage") {
    include "./components/loginFirst/loginFirst.php";
  } else if ($_GET['loginpoint'] == "sellerLogin") {
    include "./components/sellerLogin/sellerLogin.php";
  } else if ($_GET['loginpoint'] == "registerseller") {
    include "./components/sellerSignUp/sellerSignUp.php";
  } else if ($_GET['loginpoint'] == "customerLogin") {
    include "./components/customerLogin/customerLogin.php";
  } else if ($_GET['loginpoint'] == "registercustomer") {
    include "./components/customerSignUp/customerSignUp.php";
  } else {
    header('Location: ./index.php');
  }
  ?>
  <!-- LoginFirst Login Ends -->
  <footer>
    <?PHP
    include "./components/footer/footer.php";
    ?>
  </footer>
  <!-- Footer Ends -->
  <script src="./js/main.js"></script>
</body>

</html>