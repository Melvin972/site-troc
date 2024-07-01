<?php
require_once('include/init.php');

// redirection si le user est déjà connecté
if(internauteConnecte()){
    header('location:' . URL . 'profil.php');
    exit();
}

if(isset($_GET['action']) && $_GET['action'] == 'validate'){
    $validate .= '<div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <strong>Félicitations !</strong> Votre inscription est réussie 😉, vous pouvez vous connecter !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
}

// validation du formulaire de connexion
if($_POST){

    // requete sql qui récupère les infos de la personne qui veut se connecter pour comparer son pseudo avec celui existant en BDD (pour etre sur qu'il s'est déjà inscrit)
    // on récupère la totalité des infos avec * car j'en aurais besoin pour créer une session user avec toutes les infos le concernant
    $verifUser = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verifUser->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $verifUser->execute();

    if($verifUser->rowCount() == 1 ){
        // on tape la suite du code pour connecter le user
        // je dois faire un fetch, après un prepare ou query pour récupérer les valeurs en BDD
        // j'en ai besoin, si je veux comparer les deux mdp, puis créer une session user collectant les infos de la personne qui se connecte
        $connecte = $verifUser->fetch(PDO::FETCH_ASSOC);
        // condition qui vérifie si le mot de passe est similaire (entre celui de la pBDD et celui inséré dans le form)
        // je dois au préalable déhasher le mdp en BDD pour pouvoir le comparer avec celui envoyé dans le form
        // sinon, aucune valeur en BDD ne correspondra
        // password_verify va faire ce travail, en lui affectant les deux arguments
        if(password_verify($_POST['mdp'], $connecte['mdp'])){
           
            foreach($connecte as $key => $value ){
                // elle exclut cependant la valeur du mdp, interdit dans une session
                if($key != 'mdp'){
                    $_SESSION['membre'][$key] = $value;
                    // selon le statut de la personne qui se connecte, je fais une redirection
                    if(internauteConnecteAdmin()){
                        // si c'est un administrateur, je le redirige vers le back-office, avec un message de bienvenue
                        header('location:' . URL . 'profil.php?action=validate');
                    }else{
                        // si c'est un user lambda, je le redirige vers sa page profil
                        header('location:' . URL . 'profil.php?action=validate');
                    }
                }
            }
        }else{
            // le mot de passe n'est pas similaire, on avertit que le user a du se tromper
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur, le mot de passe n\'est pas reconnu, vous vous etes trompé</div>';
        }
    }else{
        // si rowCount != 1, alors, n'existe pas en BDD, on génère un message d'erreur
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur, votre pseudo n\'a pas été retrouvé. Vous l\'avez peut-etre mal renseigné ? Peut-etre n\'etes vous pas encore inscrit ? !</div>';
    }

}

require_once('include/header.php');
?>

<h2 class="text-center py-5"><div class="badge badge-primary text-wrap p-3">Connexion</div></h2>

<?= $erreur ?>

<?= $validate ?>


<form class="my-5" method="POST" action="">

    <div class="col-md-4 offset-md-4 my-4">

    <label class="form-label" for="pseudo"><div class="badge badge-dark text-wrap">Pseudo</div></label>
    <input class="form-control btn btn-outline-primary mb-4" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">

    <label class="form-label" for="mdp"><div class="badge badge-dark text-wrap">Mot de passe</div></label>
    <input class="form-control btn btn-outline-primary mb-4" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">

    <button type="submit" class="btn btn-lg btn-outline-primary offset-md-4 my-2">Connexion</button>

    </div>
   
</form>

<?php require_once('include/footer.php');