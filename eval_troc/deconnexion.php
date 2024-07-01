<?php

require_once('include/init.php');


session_destroy();

$_SESSION['message'] = "Vous êtes bien déconnecté.";
header('location:' . URL . 'index.php');
exit();
?>
