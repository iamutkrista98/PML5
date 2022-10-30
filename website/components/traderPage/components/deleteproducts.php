<?PHP
   include('connection.php');
   $id = $_GET['productid'];

   $qry = oci_parse($conn, "DELETE FROM PRODUCT WHERE PRODUCT_ID = '$id'");
   oci_execute($qry);

   header("Location: {$_SERVER['HTTP_REFERER']}");
?>