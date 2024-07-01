<?php
require_once('include/init.php');

// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if(!internauteConnecte()){
    header('location:' . URL . 'connexion.php');
    exit();
}


require_once('include/header.php');
?>

<div class="row justify-content-around py-5">
    <div class="col-md-6">
        <h3>Modifier les informations personnelles</h3>
        <form id="formModificationProfil" action="traitement_modification_profil.php" method="post">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= $_SESSION['membre']['nom'] ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $_SESSION['membre']['prenom'] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $_SESSION['membre']['email'] ?>">
            </div>
            <div class="form-group">
                <label for="statut">Statut :</label>
                <input type="text" class="form-control" id="statut" name="statut" value="<?= $_SESSION['membre']['statut'] ?>">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
</div>

<?php require_once('include/footer.php'); ?>