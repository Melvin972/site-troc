<?php
require_once('include/init.php');

if (internauteConnecte()) {
    header('location:' . URL . 'profil.php');
    exit();
}

if ($_POST) {


    if (!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9- _.]{3,20}$#', $_POST['pseudo'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
    }

    if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 8 || strlen($_POST['mdp']) > 30) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format mot de passe !</div>';
    }

    if (!isset($_POST['nom']) || !preg_match('#^[a-zA-Z -]{3,20}$#', $_POST['nom'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
    }

    if (!array_key_exists('prenom', $_POST) || !preg_match('#^[a-zA-Z -]{3,20}$#', $_POST['prenom'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prénom !</div>';
    }

    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
    }

    if (!ctype_digit($_POST['telephone']) == 10) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format téléphone !</div>';
    }

    if (!isset($_POST['civilite']) || $_POST['civilite'] != 'f' && $_POST['civilite'] != 'h') {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format civilité !</div>';
    }



    $verifPseudo = $pdo->prepare(" SELECT pseudo FROM membre WHERE pseudo = :pseudo ");
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifPseudo->execute();


    if ($verifPseudo->rowCount() == 1) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur ce pseudo est déjà utilisé par qlq\'un. Choisissez-en un autre</div>';
    }

    $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    if (empty($erreur)) {
        $addUser = $pdo->prepare(" INSERT INTO membre (pseudo, mdp, nom, prenom, email, telephone, civilite, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :telephone, :civilite, NOW()) ");
        $addUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $addUser->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
        $addUser->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $addUser->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $addUser->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $addUser->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_INT);
        $addUser->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);

        $addUser->execute();

        header('location:' . URL . 'connexion.php?action=validate');
    }
}


require_once('include/header.php');
?>
<h2 class="text-center py-5">
    <div class="badge badge-dark text-wrap p-3">Inscription</div>
</h2>


<?= $erreur ?>

<form class="my-5" method="POST" action="">

    <div class="row">
        <div class="col-md-4 mt-5">
            <label class="form-label" for="pseudo">
                <div class="badge text-wrap">Pseudo</div>
            </label>
            <input class="form-control btn btn-outline-primary" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" max-length="20" pattern="[a-zA-Z0-9-_.]{3,20}" title="caractères alphanumériques acceptés, ainsi que les caractères spéciaux  - _ . , entre trois et vingt caractères." required>
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="mdp">
                <div class="badge text-wrap">Mot de passe</div>
            </label>
            <input class="form-control btn btn-outline-primary" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe" required>
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="nom">
                <div class="badge text-wrap">Nom</div>
            </label>
            <input class="form-control btn btn-outline-primary" type="text" name="nom" id="nom" placeholder="Votre nom">
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="prenom">
                <div class="badge text-wrap">Prénom</div>
            </label>
            <input class="form-control btn btn-outline-primary" type="text" name="prenom" id="prenom" placeholder="Votre prénom">
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="email">
                <div class="badge text-wrap">Email</div>
            </label>
            <input class="form-control btn btn-outline-primary" type="email" name="email" id="email" placeholder="Votre email" required>
        </div>

        <div class="col-md-4 mt-5">
            <label class="form-label" for="telephone">
                <div class="badge text-wrap">Téléphone</div>
            </label>
            <input class="form-control btn btn-outline-primary" type="text" name="telephone" id="telephone" placeholder="Votre téléphone">
        </div>
    </div>

    <div class="row">




        <div class="col-md-4 mt-5 pt-2">
            <p>
            <div class="badge text-wrap">Civilité</div>
            </p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite1" value="f">
                <label class="form-check-label mx-2" for="civilite1">Femme</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="civilite" id="civilite2" value="h" checked>
                <label class="form-check-label mx-2" for="civilite2">Homme</label>
            </div>
        </div>
    </div>


    <div class="col-md-1 mt-5">
        <button type="submit" class="btn btn-lg btn-outline-primary">Valider</button>
    </div>

</form>

<?php require_once('include/footer.php');
