<?php
session_start();
if (!isset($_SESSION['TRADER_ID'])) {
  header('Location:login.php?loginpoint=sellerLogin');
}
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
  <link rel="stylesheet" href="./css/myaccount.css">
  <link rel="stylesheet" href="./components/traderPage/traderPage.css">
  <script src='https://cdn.tiny.cloud/1/40nhu16mcotil2oh3comjacnk6a8kzl119mtrzwikm44xeel/tinymce/5/tinymce.min.js' referrerpolicy="origin">
  </script>
  <script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
  <title>DBQ Mart - Trader</title>
</head>

<body id="body">

  <?PHP
  include "./components/darkToggle/darkToggle.php";
  ?>
  <!-- Dark Toggle Ends -->
  <?PHP
  include "./components/searchbox/searchbox.php";
  ?>
  <!-- SearchBox Ends -->
  <?PHP
  include "./components/traderPage/traderPage.php";
  ?>
  <!-- Trader Page Ends -->

  <script src="./js/dark.js"></script>
</body>

</html>