<?php
// admin/admin_logout.php
session_start();
session_destroy();
header("Location: ../login.php");
exit;
?>