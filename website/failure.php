<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./images/logo-no-tag-svg.svg">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/payment.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <link rel="stylesheet" href="./css/colors.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <title>Payment Successful</title>
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
    <div class='payment'>
        <div class='checked'>
            <img src="images/payment/cancel.png" alt="">
            <h1 class="pay-title">Payment Cancelled For Some Reason...!</h1>
        </div>
        <a href='viewcustomerorders.php'>
            <h3 align='center'><i class='bx bx-package'></i> Check Orders</h3>
        </a>
        <a href='index.php'>
            <h3 align='center'><i class='bx bx-home'></i> Go Home</h3>
        </a>
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