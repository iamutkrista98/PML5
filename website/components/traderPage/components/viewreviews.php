<h1 class='accheading'>Reviews And Rating</h1>
<table class="table">
    <thead>
        <th>Product</th>
        <th>Review</th>
        <th>Rating</th>
        <th>Posted On</th>
        <th>Posted By</th>
    </thead>
    <tbody>
        <?php
        include('./connection.php');
        $tid = $_SESSION['TRADER_ID'];
        $fetchreview = oci_parse($conn, "SELECT DISTINCT P.PRODUCT_NAME,R.REVIEW_DESCRIPTION,R.REVIEW_DATE,R.RATING,C.CUSTOMER_NAME FROM REVIEW R ,ORDERS_PRODUCT OP,PRODUCT P,SHOP S,TRADER T,CUSTOMER C,ORDERS O WHERE OP.FK1_PRODUCT_ID=P.PRODUCT_ID AND S.SHOP_ID=P.FK1_SHOP_ID AND S.FK1_USER_ID=T.USER_ID AND R.FK1_PRODUCT_ID=P.PRODUCT_ID AND O.CUSTOMER_ID=C.USER_ID AND R.FK2_USER_ID=C.USER_ID AND T.USER_ID='$tid' AND R.STATUS=1");
        oci_execute($fetchreview);
        while ($fetch = oci_fetch_assoc($fetchreview)) {

        ?>
            <tr>
                <td data-label="Product">
                    <?php echo $fetch['PRODUCT_NAME'] ?>
                </td>
                <td data-label="Review"><?php echo $fetch['REVIEW_DESCRIPTION']; ?></td>
                <div class='rating'>
                    <?php $star = $fetch['RATING'];
                    if ($star == 1) {
                        $rate = "<i class='bx bxs-star'></i><i class='bx bx-star'></i><i class='bx bx-star'></i><i class='bx bx-star'></i><i class='bx bx-star'></i>";
                    }
                    if ($star == 2) {
                        $rate = "<i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bx-star'></i><i class='bx bx-star'></i><i class='bx bx-star'></i>";
                    }
                    if ($star == 3) {
                        $rate = "<i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'><i class='bx bx-star'></i><i class='bx bx-star'></i></i>";
                    }
                    if ($star == 4) {
                        $rate = "<i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bx-star'></i>";
                    }
                    if ($star == 5) {
                        $rate = "<i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i>";
                    }

                    ?>
                    <td data-label="Rating"><?php echo $rate; ?></td>
                </div>
                <td data-label="Posted On"><?php echo $fetch['REVIEW_DATE'] ?></td>
                <td data-label="Posted By"><?php echo $fetch['CUSTOMER_NAME'] ?></td>

            </tr>

        <?php } ?>
    </tbody>
</table>