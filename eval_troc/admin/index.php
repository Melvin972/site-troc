<?php
require_once('includeAdmin/header.php');


if (!internauteConnecteAdmin()) {
    header('location:' . URL . 'connexion.php');
    echo 'Vous n\'etes pas admin';
    exit();
}


if (internauteConnecte() && $_SESSION['membre']['statut'] == 1) {
    return TRUE;
} else {
    return FALSE;
}

?>






<?php
require_once('includeAdmin/footer.php');