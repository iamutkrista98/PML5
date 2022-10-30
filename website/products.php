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
  <link rel="stylesheet" href="./css/colors.css">
  <link rel="stylesheet" href="./components/loginPageWindow/loginPageWindow.css">
  <link rel="stylesheet" href="./components/productsPage/productsPage.css">
  <title>DBQ Mart - Products</title>
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
  include "./components/productsPage/productsPage.php";
  ?>
  <!-- Products Page Ends -->
  <?PHP
  include "./components/footer/footer.php";
  ?>
  <script src="./js/main.js"></script>
  <script src="./js/login.js"></script>
  <script src="./js/product-filter.js"></script>
</body>

</html>