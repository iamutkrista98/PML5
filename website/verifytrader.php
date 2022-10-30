<?php
require('connection.php');
include('./sendmail.php');
include('./function.php');
?>

<?php
if (isset($_GET['email']) && isset($_GET['tname']) && isset($_GET['sname']) && isset($_GET['saddress']) && isset($_GET['tphone']) && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['tcat'])) {
    $temail = trim($_GET['email']);
    $tname = trim($_GET['tname']);
    $tphone = trim($_GET['tphone']);
    $tusername = trim($_GET['username']);
    $tpassword = trim($_GET['password']);
    $sname = trim($_GET['sname']);
    $saddress = trim($_GET['saddress']);
    $tcat = trim($_GET['tcat']);

    $checkquery = oci_parse($conn, "SELECT * FROM TRADER WHERE EMAIL_ADDRESS='$temail' AND TRADER_NAME='$tname' AND STATUS=1");
    oci_execute($checkquery);
    $count = 0;


    while ($row = oci_fetch_array($checkquery)) {
        $count = oci_num_rows($checkquery);
    }
    if ($count > 0) {
        echo "<script>alert('Trader Already Registered');</script>";
    } else {


        $res = oci_parse($conn, "INSERT INTO TRADER (TRADER_NAME,USERNAME,PASSWORD,STATUS,EMAIL_ADDRESS,CONTACT_NO) VALUES('$tname','$tusername','$tpassword',1,'$temail','$tphone')");
        oci_execute($res);
        $apex = oci_parse($conn, "UPDATE TRADER_REQUEST SET STATUS=1 WHERE GENERATED_USERNAME='$tusername' AND GENERATED_PASSWORD='$tpassword'");
        oci_execute($apex);
        $shopinsert = oci_parse($conn, "SELECT * FROM TRADER WHERE USERNAME='$tusername' AND PASSWORD='$tpassword'");
        oci_execute($shopinsert);
        while ($fetchtid = oci_fetch_assoc($shopinsert)) {
            $tid = $fetchtid['USER_ID'];
        }
        $insertshop = oci_parse($conn, "INSERT INTO SHOP(SHOP_NAME,FK1_USER_ID) VALUES('$sname','$tid')");
        oci_execute($insertshop);
        $insertcat = oci_parse($conn, "INSERT INTO PRODUCT_TYPE(PRODUCT_TYPE_NAME,PRODUCT_TYPE_DESCRIPTION) VALUES('$tcat','$tcat')");
        oci_execute($insertcat);
        echo "<script>alert('Trader Authenticated');
        </script>";

        $html = "<head><style>.mail{text-align:justify;}</style></head><body><div class='mail'>Dear Trader,<br>We have approved your request to join at The Daily Bread Quintessential (DBQ MART) as a trader. Your login credentials are here in attached for reference.
        Your Login Credentials are as follows:<br>
            <h3>Username: $tusername</h3><h3>Password: $tpassword</h3><br>THANKS, DBQ MART TEAM</div></body>";
        send_email($temail, $html, 'Administration Approval For Your Registration Successful');
    }
} else {
    header('Location:index.php');
}
?>