<?php
session_start();
include('function.php');

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
    <link rel="stylesheet" href="./css/colors.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <title>Order Items</title>
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

    <?php
    include('connection.php');
    $id = '';

    if (!isset($_SESSION['USER_ID'])) {
        header('Location:login.php?loginpoint=customerLogin');
    } else {
        if ($_GET['id']) {
            $id = $_GET['id'];
        }
    }



    ?>



    <div class='account'>
        <h1 class='accheading'><?php echo $_GET['id']; ?></h1>
        <table class="table">
            <thead>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Sub Total</th>
            </thead>
            <tbody>
                <?php
                $pp = 0;
                $getOrderDetails = getOrderDetails($id);
                foreach ($getOrderDetails as $list) {
                    $pp = $pp + ($list['QUANTITY'] * $list['UNIT_PRICE']);
                ?>
                    <tr>
                        <td data-label="Product">
                            <?php echo $list['PRODUCT_NAME'] ?>
                        </td>
                        <td data-label="Unit Price"><?php echo '£ ' . $list['UNIT_PRICE'] ?></td>
                        <td data-label="Quantity"><?php echo $list['QUANTITY'] ?></td>
                        <td data-label="Total Price"><?php echo '£ ' . $list['QUANTITY'] * $list['UNIT_PRICE'] ?></td>

                    </tr>

                <?php } ?>
                <tr>
                    <td colspan="2"></td>
                    <td><strong>Total:</strong></td>
                    <td><strong><?php echo '£ ' . $pp; ?></strong></td>
                </tr>
            </tbody>
        </table>

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