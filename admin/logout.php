<?php
session_start();
unset($_SESSION["adminuser"]);
session_destroy();
echo '<script language=javascript> localStorage.removeItem("activeTab"); location.href="index.php";</script>';
?>