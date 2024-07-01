<?php
require_once('include/init.php');

if (!internauteConnecte()) {
    header('location:' . URL . 'connexion.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'validate') {
    $validate .= '<div class="alert alert-primary alert-dismissible fade show mt-5" role="alert">
                    Félicitations <strong>' . $_SESSION['membre']['pseudo'] .'</strong>, vous êtes connecté(e) 😉 !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'; 
}

require_once('include/header.php');
?>

<h2 class="text-center my-5"><div class="badge badge-primary text-wrap p-3">Bonjour <?= $_SESSION['membre']['pseudo'] ?></div></h2>

<?= $validate ?>

<div class="row justify-content-around py-5">
    <div class="col-md-6">
        <h3>Informations Personnelles</h3>
        <form action="modifier_profil.php" method="post">
            <div class="form-group">
                <label for="pseudo">Pseudo :</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?= $_SESSION['membre']['pseudo'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= $_SESSION['membre']['nom'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $_SESSION['membre']['prenom'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['membre']['email'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="statut">Statut :</label>
                <input type="text" class="form-control" id="statut" name="statut" value="<?= $_SESSION['membre']['statut'] ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Modifier mes informations</button>
        </form>
    </div>
</div>

<?php require_once('include/footer.php'); ?>
