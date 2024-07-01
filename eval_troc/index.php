<?php
require_once('include/init.php');




require_once('include/header.php');



if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
};



?>
<?php 
require_once('include/affichage.php'); 
?>



<?php require_once('include/footer.php');
