<?php
// Inclusion du fichier d'en-tête
require_once('includeAdmin/header.php');

// Initialisation de la variable d'erreur
$erreur = '';

// Vérification de l'action à effectuer
if (isset($_GET['action'])) {

    // Traitement du formulaire soumis
    if ($_POST) {

        // Validation des données du formulaire
        // Vérification du format du pseudo
        if (!isset($_POST['pseudo']) || !preg_match('#^[a-zA-Z0-9- _.]{3,20}$#', $_POST['pseudo'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
        }

        // Vérification du format du nom
        if (!isset($_POST['nom']) || !preg_match('#^[a-zA-Z -]{3,20}$#', $_POST['nom'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format nom !</div>';
        }

        // Vérification du format du prénom
        if (!array_key_exists('prenom', $_POST) || !preg_match('#^[a-zA-Z]{3,20}$#', $_POST['prenom'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prénom !</div>';
        }

        // Vérification du format de l'email
        if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format email !</div>';
        }

        // Vérification du format du téléphone
        if (!ctype_digit($_POST['telephone']) || strlen($_POST['telephone']) != 10) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format téléphone !</div>';
        }

        // Vérification du format du statut
        if (!ctype_digit($_POST['statut']) || $_POST['statut'] != 1) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format statut !</div>';
        }

        // Vérification du format de la civilité
        if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'f' && $_POST['civilite'] != 'h')) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format civilité !</div>';
        }

        // Hachage du mot de passe
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

        // Si aucune erreur, procéder à la mise à jour
        if (empty($erreur)) {
            if ($_GET['action'] == 'update') {
                // Préparation de la requête de mise à jour
                $modifMembre = $pdo->prepare("UPDATE membre SET 
                                                id_membre = :id_membre,
                                                pseudo = :pseudo, 
                                                nom = :nom, 
                                                prenom = :prenom, 
                                                telephone = :telephone, 
                                                email = :email, 
                                                civilite = :civilite,
                                                statut = :statut
                                                WHERE id_membre = :id_membre
                ");
                // Liaison des valeurs
                $modifMembre->bindValue(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
                $modifMembre->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $modifMembre->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $modifMembre->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $modifMembre->bindValue(':telephone', $_POST['telephone'], PDO::PARAM_STR);
                $modifMembre->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $modifMembre->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                $modifMembre->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);

                // Exécution de la requête de mise à jour
                $modifMembre->execute();
            }
        }
    }

    // Récupération des données du membre à modifier
    if ($_GET['action'] == 'update') {
        $updateMembre = $pdo->query("SELECT * FROM membre WHERE id_membre ='$_GET[id_membre]' ");
        $membreActuel = $updateMembre->fetch(PDO::FETCH_ASSOC);
    }
}

// Affectation des valeurs par défaut si aucun membre n'est sélectionné
$id_membre = (isset($membreActuel['id_membre'])) ? $membreActuel['id_membre'] : "";
$pseudo = (isset($membreActuel['pseudo'])) ? $membreActuel['pseudo'] : "";
$nom = (isset($membreActuel['nom'])) ? $membreActuel['nom'] : "";
$prenom = (isset($membreActuel['prenom'])) ? $membreActuel['prenom'] : "";
$email = (isset($membreActuel['email'])) ? $membreActuel['email'] : "";
$civilite = (isset($membreActuel['civilite'])) ? $membreActuel['civilite'] : "";
$telephone = (isset($membreActuel['telephone'])) ? $membreActuel['telephone'] : "";
$statut = (isset($membreActuel['statut'])) ? $membreActuel['statut'] : "";

// Récupération de la liste des membres
$rechercheMembres = $pdo->query("SELECT * FROM membre");
?>
<h1 class="text-center my-5">
    <div class="badge badge-primary text-wrap p-3">Gestion des membres</div>
</h1>

<div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-primary rounded" role="alert">
    <p>Gérez ici votre base de données des membres</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer un membre</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'update') {
?>
    <form class="my-5" method="POST" action="?action=update&id_membre=<?= $membreActuel['id_membre'] ?>">
        <?= $erreur ?>

        <div class="row">
            <div class="col-md-4 mt-5">
                <input type="hidden" name="id_membre" value="<?= $id_membre ?>">

                <label class="form-label" for="pseudo">
                    <div class="badge text-wrap">Pseudo</div>
                </label>
                <input class="form-control btn btn-outline-primary" type="text" name="pseudo" id="pseudo" value="<?= $pseudo ?>" placeholder="Votre pseudo" max-length="20" pattern="[a-zA-Z0-9-_.]{3,20}" title="caractères alphanumériques acceptés, ainsi que les caractères spéciaux  - _ . , entre trois et vingt caractères." required>
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
                <input class="form-control btn btn-outline-primary" type="text" name="nom" id="nom" value="<?= $nom ?>" placeholder="Votre nom">
            </div>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="prenom">
                    <div class="badge text-wrap">Prénom</div>
                </label>
                <input class="form-control btn btn-outline-primary" type="text" name="prenom" id="prenom" value="<?= $prenom ?>" placeholder="Votre prénom">
            </div>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="email">
                    <div class="badge text-wrap">Email</div>
                </label>
                <input class="form-control btn btn-outline-primary" type="email" name="email" id="email" value="<?= $email ?>" placeholder="Votre email" required>
            </div>

            <div class="col-md-4 mt-5">
                <label class="form-label" for="telephone">
                    <div class="badge text-wrap">Téléphone</div>
                </label>
                <input class="form-control btn btn-outline-primary" type="text" name="telephone" id="telephone" value="<?= $telephone ?>" placeholder="Votre téléphone">
            </div>
            <div class="col-md-4 mt-5">
                <label class="form-label" for="statut">
                    <div class="badge text-wrap">Statut</div>
                </label>
                <input class="form-control btn btn-outline-primary" type="num" name="statut" id="statut" value="<?= $statut ?>" placeholder="Votre statut">
            </div>
        </div>

        <div class="row">

            <div class="col-md-4 mt-5 pt-2">
                <p>
                <div class="badge text-wrap">Civilité</div>
                </p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="civilite" id="civilite1" value="f" <?= ($civilite == "f") ? "checked" : "" ?>>
                    <label class="form-check-label mx-2" for="civilite1">Femme</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="civilite" id="civilite2" value="h" <?= ($civilite == "h") ? "checked" : "" ?>>
                    <label class="form-check-label mx-2" for="civilite2">Homme</label>
                </div>
            </div>
        </div>


        <div class="col-md-1 mt-5">
            <button type="submit" class="btn btn-lg btn-outline-primary">Valider</button>
        </div>

    </form>
<?php } ?>


<?php if (isset($_GET['action'])) : ?>

    <?php if ($_GET['action'] == 'delete') {
        // Préparation de la requête
        $deleteMembre = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");

        // Liaison des paramètres
        $deleteMembre->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);

        // Exécution de la requête
        if ($deleteMembre->execute()) {
            echo "Le membre a été supprimé avec succès.";
        } else {
            echo "Une erreur est survenue lors de la suppression.";
        }
    } ?>
<?php endif; ?>




<!-- Tableau -->

<?php $nbMembres = $pdo->query(" SELECT id_membre FROM membre ") ?>
<h1 class="mt-5 text-center text-secondary">Nombre de membres enregistrées : <?= $nbMembres->rowCount() ?></h1>




<table class="table table-hover bg-light mt-5 rounded shadow ">
    <thead>
        <tr class="table-secondary">
            <?php for ($i = 0; $i < $rechercheMembres->columnCount(); $i++) {
                $colonne = $rechercheMembres->getColumnMeta($i);
                if ($colonne['name'] != 'mdp') { ?>
                    <th><?= $colonne['name'] ?></th>
            <?php }
            } ?>
            <th colspan='2'>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($membre = $rechercheMembres->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <?php foreach ($membre as $key => $value) : ?>
                    <?php if ($key != 'mdp') { ?>
                        <?php if ($key == 'photo') : ?>
                            <td><img src='<?= URL . 'img/' . $value ?>' alt='' width='40'></td>
                        <?php elseif ($key == 'prix') : ?>
                            <td><?= $value ?> €</td>
                        <?php else : ?>
                            <td><?= $value ?></td>
                        <?php endif; ?>
                    <?php } ?>
                <?php endforeach; ?>

                <td class="text-center">
                    <a href='?action=update&id_membre=<?= $membre['id_membre'] ?>'><i class="bi bi-pen-fill text-warning"></i></a>
                </td>
                <td class="text-center">
                    <a href='?action=delete&id_membre=<?= $membre['id_membre'] ?>' toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger"></i></a>
                </td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-end">
        <li class="page-item">
            <a class="page-link text-dark" href="" aria-label="Previous">
                <span aria-hidden="true">&laquo; Précédent</span>
                <span class="sr-only">Previous</span>
            </a>
        </li>



        <li class="page-item">
            <a class="page-link text-dark" href="" aria-label="Next">
                <span aria-hidden="true">Suivant &raquo;</span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</nav>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Supprimer membre
            </div>
            <div class="modal-body">
                Etes-vous sur de vouloir retirer ce membre ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Non</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>
<?php require_once('includeAdmin/footer.php'); ?>