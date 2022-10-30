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
  <link rel="stylesheet" href="./components/homeHero/homeHero.css">
  <link rel="stylesheet" href="./components/homeTraders/homeTraders.css">
  <link rel="stylesheet" href="./components/homeDeals/homDeals.css">
  <link rel="stylesheet" href="./components/collectionPointBanner/collectionPointBanner.css">
  <link rel="stylesheet" href="./components/features/features.css">
  <link rel="stylesheet" href="./components/foundAnythingBanner/foundAnythingBanner.css">
  <title>Daily Bread Quintessential</title>
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
  <!-- Dark Toggle Ends -->
  <?PHP
  include "./components/homeHero/homeHero.php";
  ?>
  <!-- Hero Ends -->
  <?PHP
  include "./components/homeTraders/homeTraders.php";
  ?>
  <!-- Traders Ends -->
  <?PHP
  include "./components/homeDeals/homeDeals.php";
  ?>
  <!-- Deals Ends -->
  <?PHP
  include "./components/collectionPointBanner/collectionPointBanner.php";
  ?>
  <!-- Collection Point Ends -->
  <?PHP
  include "./components/homeFeatured/homeFeatured.php";
  ?>
  <!-- Featured Products Ends -->
  <?PHP
  include "./components/features/features.php";
  ?>
  <!-- Features Ends -->
  <?PHP
  include "./components/foundAnythingBanner/foundAnythingBanner.php";
  ?>
  <!-- Found Anything Banner Ends  -->
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