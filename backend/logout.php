<?php
session_start();

$_SESSION = array();

session_destroy();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("pragma: no-cache");
header("Expires: 0");
header("Location: ../admin/admin_login_1002.php");
exit();
?>