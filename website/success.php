<?php
include('function.php');
include('smtp/PHPMailerAutoload.php');
include('sendmail.php');
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
            <img src="images/payment/checked.png" alt="">
            <h1 class="pay-title">Payment Done Successfully...!</h1>
        </div>
        <?php
        //$catt = getUserOrderId();

        $payment_status = $_POST['payment_status'];
        $payment_date = $_POST['payment_date'];
        $payment_gross = trim($_POST['payment_gross']);
        $uid = $_POST['item_number'];
        $oid = $_POST['item_name'];

        $payamt = number_format($payment_gross, 2);


        echo "<h3>Order ID: $oid| Payment Status='$payment_status'| Payment Date='$payment_date'| Payment Gross='$payment_gross'</h3>";
        if ($payment_status == 'Completed') {
            include('connection.php');
            $complete = 1;
            $sql = "update ORDERS set PAYMENT_STATUS='$complete',ORDER_STATUS=1 WHERE CUSTOMER_ID='$uid' AND ORDER_ID='$oid'";
            $res = oci_parse($conn, $sql);
            $check = oci_execute($res);
            if ($check) {
                $verpay = "INSERT INTO PAYMENT(PAYMENT_DATE,TOTAL_AMOUNT,FK1_USER_ID,FK2_ORDER_ID) VALUES('$payment_date','$payamt','$uid','$oid')";
                $verpayres = oci_parse($conn, $verpay);
                oci_execute($verpayres);

                $verpay1 = "SELECT * FROM PAYMENT WHERE FK1_USER_ID = '$uid' AND FK2_ORDER_ID = '$oid'";
                $verpayres1 = oci_parse($conn, $verpay1);
                $success = oci_execute($verpayres1);
                while ($verpayres1check = oci_fetch_assoc($verpayres1)) {
                    $payid = $verpayres1check['PAYMENT_ID'];
                }
                $insertinvoice = oci_parse($conn, "INSERT INTO RECEIPT(FK1_USER_ID,FK2_PAYMENT_ID) VALUES('$uid','$payid')");
                $success1 = oci_execute($insertinvoice);


                $findinvoice = oci_parse($conn, "SELECT * FROM RECEIPT WHERE FK1_USER_ID='$uid' AND FK2_PAYMENT_ID='$payid'");
                oci_execute($findinvoice);
                while ($fetchinvoice = oci_fetch_assoc($findinvoice)) {
                    $rinvoice = $fetchinvoice['RECEIPT_ID'];
                }



                $emptycart = "DELETE FROM CART_PRODUCT WHERE ADDED_BY='$uid'";
                $empres = oci_parse($conn, $emptycart);
                oci_execute($empres);
                $emptyparentcart = oci_parse($conn, "DELETE FROM CART WHERE FK1_USER_ID='$uid'");
                oci_execute($emptyparentcart);

                include('connection.php');
                $findemail = oci_parse($conn, "SELECT * FROM CUSTOMER WHERE USER_ID='$uid'");
                oci_execute($findemail);
                while ($checkemail = oci_fetch_assoc($findemail)) {
                    $remail = $checkemail['EMAIL_ADDRESS'];
                    $rname = $checkemail['CUSTOMER_NAME'];
                }
                $html = send_receipt($oid, $payment_gross, $payment_date, $rname, $rinvoice);
                send_email($remail, $html, "Receipt For Recent Purchase");
            } else {
            }
        }
        ?>
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