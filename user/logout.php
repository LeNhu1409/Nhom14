<?php
session_start();
session_destroy();
header("Location: index.php"); // hoặc login.php
exit();
?>
