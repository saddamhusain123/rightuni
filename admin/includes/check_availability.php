<?php
	if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='reporter'){
      header('Location: 404.php');
    }
?>