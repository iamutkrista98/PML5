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
    <link rel="stylesheet" href="./css/myaccount.css">
    <link rel="stylesheet" href="./css/colors.css">
    <link rel="stylesheet" href="./css/fonts.css">
    <title>My Orders</title>
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

    if (!isset($_SESSION['USER_ID'])) {
        header('Location:login.php?loginpoint=customerLogin');
    } else {
        $uid = $_SESSION['USER_ID'];
        $sql = "SELECT * FROM ORDERS WHERE CUSTOMER_ID='$uid' ORDER BY ORDER_DATE DESC";
        $res = oci_parse($conn, $sql);
        oci_execute($res);
    }



    ?>



    <div class='account'>
        <h1 class='accheading'>My Orders</h1>
        <table class="table">
            <thead>
                <th>Order ID</th>
                <th>Order date</th>
                <th>Payment Type</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Collection Date</th>
                <th>Time Slot</th>
            </thead>
            <?php
            $i = 1;
            while ($row = oci_fetch_assoc($res)) {
                //prx($row);
            ?>
                <tbody>
                    <tr>
                        <td data-label="Order ID">
                            <a href="<?php echo 'customerorderdetail.php?id=' . $row['ORDER_ID'] ?>"><?php echo $row['ORDER_ID']; ?></a>
                        </td>
                        <td data-label="Order date"><?php echo $row['ORDER_DATE'] ?></td>
                        <td data-label="Payment Type">PayPal</td>
                        <td data-label="Payment Status">
                            <?php
                            if ($row['PAYMENT_STATUS'] == 0) {
                                $pstatus = 'Pending';
                            } else {
                                $pstatus = 'Complete';
                            }
                            ?>
                            <div class="payment_status1 payment_status_<?php echo $row['PAYMENT_STATUS'] ?>"><?php echo $pstatus; ?></div>
                        </td>
                        <td data-label="Order Status">
                            <?php
                            if ($row['ORDER_STATUS'] == 0) {
                                $ostatus = 'Pending';
                            } else {
                                $ostatus = 'Complete';
                            }
                            ?>
                            <div class="order_status1 order_status_<?php echo $row['ORDER_STATUS'] ?>">
                                <?php echo $ostatus; ?></div>

                        </td>
                        <td data-label="Collection Day"><?php echo $row['COLLECTION_DATE']; ?></td>
                        <td data-label="Collection Slot"><?php echo $row['TIME_SLOT']; ?></td>
                    </tr>
                </tbody>


            <?php } ?>
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