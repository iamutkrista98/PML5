<?php
session_start();
session_destroy();
echo "<script>alert('Logged Out Successfully!')</script>";
echo "<script>window.location = './index.php'</script>";
