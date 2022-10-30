<?php
session_start();
include('../function.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>

    <!-- FONT AWESOME ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />

    <link rel="stylesheet" href="payment.css">

</head>

<body>
    <main id="cart-main">

        <div class="site-title text-center">
            <div><img src="./assets/checked.png" alt=""></div>
            <h1 class="font-title">Payment Done Successfully...!</h1>
            <?php
            //$catt = getUserOrderId();

            $payment_status = $_POST['payment_status'];
            $payment_date = $_POST['payment_date'];
            $payment_gross = $_POST['payment_gross'];
            $uid = $_POST['item_number'];
            $oid = $_POST['item_name'];


            echo "payment_status='$payment_status',payment_date='$payment_date',payment_gross='$payment_gross'";
            if ($payment_status == 'Completed') {
                include('../connection.php');
                $complete = 1;
                $sql = "update ORDERS set PAYMENT_STATUS='$complete',ORDER_STATUS=1 WHERE CUSTOMER_ID='$uid' AND ORDER_ID='$oid'";
                $res = oci_parse($conn, $sql);
                oci_execute($res);

                $verpay="INSERT INTO PAYMENT(PAYMENT_DATE,TOTAL_AMOUNT,FK1_USER_ID,FK2_ORDER_ID) VALUES('$payment_date','$payment_gross','$uid','$oid')";
                $verpayres=oci_parse($conn,$sql);
                oci_execute($verpayres);

                $emptycart = "DELETE FROM CART_PRODUCT WHERE ADDED_BY='$uid'";
                $empres = oci_parse($conn, $emptycart);
                oci_execute($empres);
            }
            ?>

        </div>

    </main>

</body>

</html>