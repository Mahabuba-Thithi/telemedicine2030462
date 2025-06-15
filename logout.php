<?php
session_start();
session_destroy();
header("Location: general_entry_dashboard.php");
exit();
?>
