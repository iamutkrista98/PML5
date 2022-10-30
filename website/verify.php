<?php
require('connection.php');
?>

<?php
if (isset($_GET['email']) && isset($_GET['token'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];
    $checkquery = oci_parse($conn, "SELECT * FROM CUSTOMER WHERE EMAIL_VERIFY=1 AND TOKEN='$token' AND EMAIL_ADDRESS='$email'");
    oci_execute($checkquery);
    $count = 0;
    while ($row = oci_fetch_array($checkquery)) {
        $count = oci_num_rows($checkquery);
    }
    if ($count == 1) {
        echo "<script>alert('Your email is already verified! You can go ahead and login')
        window.location.href='login.php?loginpoint=customerLogin';
        </script>";
    } else {
        $res = oci_parse($conn, "UPDATE CUSTOMER SET EMAIL_VERIFY=1 WHERE TOKEN='$token'");
        oci_execute($res);
        echo "<script>alert('Your email is verified successfully!')
            window.location.href='login.php?loginpoint=customerLogin';
            </script>";
    }
} else {
    header('Location:index.php');
}
?>
