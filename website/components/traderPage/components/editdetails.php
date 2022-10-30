<div class="traderPage-editdetails-elements">
  <?php
  $eimage = '';
  if ($_SESSION['TRADER_ID']) {
    if (isset($_POST['update'])) {
      $id = $_POST['eid'];
      $ename = $_POST['ename'];
      $eemail = $_POST['eemail'];

      $filepath = "./images/traders/" . $_FILES["file"]["name"];
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) {
      } else {
      }

      $pimg = $_FILES['file']['name'];
      include('connection.php');
      if (empty($pimg)) {
        $sql = oci_parse($conn, "UPDATE TRADER SET TRADER_NAME='$ename',EMAIL_ADDRESS='$eemail' WHERE USER_ID='$id'");
      } else {
        $sql = oci_parse($conn, "UPDATE TRADER SET TRADER_NAME='$ename',EMAIL_ADDRESS='$eemail',PROFILE_IMAGE='$pimg' WHERE USER_ID='$id'");
      }
      if (oci_execute($sql)) {
        echo "<script>alert('Details updated successfully')</script>";
      }
    }
  }










  ?>

  <!-- Dark Toggle Ends -->
  <div class='account'>
    <h1 class='accheading'>My Account Page</h1>
    <h3 align="center"> <?php if (isset($_SESSION['TRADER_NAME'])) echo $_SESSION['TRADER_NAME'];
                        else {
                        } ?></h3>
    <div class='details'>
      <?php
      include('connection.php');
      if (isset($_SESSION['TRADER_ID'])) {
        $sql = oci_parse($conn, 'SELECT * FROM TRADER');
        oci_execute($sql);
        while ($row = oci_fetch_array($sql, OCI_ASSOC + OCI_RETURN_NULLS)) {
          if (oci_result($sql, 'USER_ID') == $_SESSION['TRADER_ID']) {
            echo '<form method="post" action="" enctype="multipart/form-data">';
            $pimg = $row['PROFILE_IMAGE'];
            echo "<img src='images/traders/$pimg' class='pimage'>";
            echo '<label>Profile Image</label>';
            echo "<input type='file' name='file' placeholder='update profile';>";
            echo '<label>User Type:</label> ';
            echo "<input type='text' name='type' value='TRADER' readonly>";
            echo "<input type='hidden' name='eid' value='$row[USER_ID]' readonly>";
            echo '<label>Name:</label> ';
            echo "<input type='text' name='ename' value='$row[TRADER_NAME]'>";
            echo "<br>";
            echo '<label>Email Address:</label> ';
            echo "<input type='text' name='eemail' value='$row[EMAIL_ADDRESS]' readonly>";
            echo "<br>";
            echo "<button class='update' type='submit' name='update'><i class='bx bxs-user-check' ></i>Update</button>";
            echo "</form>";
          }
        }
      }

      ?>
    </div>
  </div>
</div>