<?php
$conn = oci_connect('DBQ', 'Secret1234!', 'localhost/XE');
if (!$conn) {
	$e = oci_error();
	trigger_error(htmlentities($e['message'] . ENT_QUOTES), E_USER_ERROR);
} else {
}
