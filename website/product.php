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
  <link rel="stylesheet" href="./components/productPage/productPage.css">
  <link rel="stylesheet" href="./components/features/features.css">
  <title>DBQ Mart - Product Title</title>
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
  <!-- SearchBox Ends -->
  <?PHP
  include "./components/productPage/productPage.php";
  ?>
  <!-- Product Page Ends -->
  <?PHP
  include "./components/footer/footer.php";
  ?>
  <!-- Footer Ends -->

  <script src="./js/main.js"></script>
  <script src="./js/product.js"></script>

</body>

</html>

</html>