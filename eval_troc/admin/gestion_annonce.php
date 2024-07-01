<?php
require_once('includeAdmin/header.php');

?>

<?php

$erreur = '';


if (isset($_GET['action'])) {
    if ($_POST) {

        if (!isset($_POST['titre']) || !preg_match('#^[a-zA-Z0-9- _.]{3,20}$#', $_POST['titre'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
        }

        if (!isset($_POST['description_courte']) || strlen($_POST['description_courte']) < 8 || strlen($_POST['description_courte']) > 30) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description courte !</div>';
        }

        if (!isset($_POST['description_longue']) || strlen($_POST['description_longue']) < 8 || strlen($_POST['description_longue']) > 255) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description longue !</div>';
        }

        if (!isset($_POST['prix']) || !preg_match('#^\d+(\.\d{1,2})?$#', $_POST['prix'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format prix !</div>';
        }

        if (!isset($_POST['categorie']) || !in_array($_POST['categorie'], range(1, 11))) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur sélection de catégorie !</div>';
        }

        if (!isset($_POST['pays']) || strlen($_POST['pays']) < 3 || strlen($_POST['pays']) > 50) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format pays !</div>';
        }

        if (!isset($_POST['ville']) || strlen($_POST['ville']) < 3 || strlen($_POST['ville']) > 50) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format ville !</div>';
        }

        if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 3 || strlen($_POST['adresse']) > 255) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format adresse !</div>';
        }

        if (!isset($_POST['cp']) || !preg_match('#^[0-9]{5}$#', $_POST['cp'])) {
            $erreur .= '<div class="alert alert-danger" role="alert">Erreur format code postal !</div>';
        }

        if (empty($erreur)) {
            if ($_GET['action'] == 'update') {


                $modifAnnonce = $pdo->prepare("UPDATE annonce SET 
                                            titre = :titre, 
                                            description_courte = :description_courte, 
                                            description_longue = :description_longue, 
                                            prix = :prix, 
                                            categorie_id = :categorie_id, 
                                            pays = :pays,
                                            ville = :ville,
                                            adresse = :adresse,
                                            cp = :cp
                                            WHERE id_annonce = :id_annonce
            ");
                $modifAnnonce->bindValue(':id_annonce', $_GET['id_annonce'], PDO::PARAM_INT);
                $modifAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
                $modifAnnonce->bindValue(':categorie_id', $_POST['categorie'], PDO::PARAM_INT);
                $modifAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
                $modifAnnonce->bindValue(':cp', $_POST['cp'], PDO::PARAM_INT);


                $modifAnnonce->execute();
            }
        }
    }
    if ($_GET['action'] == 'update') {
        $updateAnnonce = $pdo->query("SELECT * FROM annonce WHERE id_annonce ='$_GET[id_annonce]' ");
        $annonceActuel = $updateAnnonce->fetch(PDO::FETCH_ASSOC);
    }
}


$titre = (isset($annonceActuel['titre'])) ? $annonceActuel['titre'] : "";
$description_courte = (isset($annonceActuel['description_courte'])) ? $annonceActuel['description_courte'] : "";
$description_longue = (isset($annonceActuel['description_longue'])) ? $annonceActuel['description_longue'] : "";
$prix = (isset($annonceActuel['prix'])) ? $annonceActuel['prix'] : "";
$categorie = (isset($annonceActuel['categorie_id'])) ? $annonceActuel['categorie_id'] : "";
$pays = (isset($annonceActuel['pays'])) ? $annonceActuel['pays'] : "";
$ville = (isset($annonceActuel['ville'])) ? $annonceActuel['ville'] : "";
$adresse = (isset($annonceActuel['adresse'])) ? $annonceActuel['adresse'] : "";
$cp = (isset($annonceActuel['cp'])) ? $annonceActuel['cp'] : "";

?>

<h1 class="text-center my-5">
    <div class="badge badge-primary text-wrap p-3">Gestion des annonces</div>
</h1>

<div class="blockquote alert alert-dismissible fade show mt-5 shadow border border-primary rounded" role="alert">
    <p>Gérez ici votre base de données des annonces</p>
    <p>Vous pouvez modifier leurs données, ajouter ou supprimer une annonce</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == 'update') :
?>
    <div class="container-fluid mt-5 bg-light p-4 rounded">
        <h2 class="mb-4 text-primary">Modifier l'annonce</h2>
        <form action="" method="POST" enctype="multipart/form-data">

            <!-- Titre -->
            <div class="mb-3">
                <label for="titre" class="form-label text-secondary">Titre de l'annonce :</label>
                <input type="text" class="form-control" id="titre" value="<?= $titre ?>" name="titre" required>
            </div>

            <!-- Description courte -->
            <div class="mb-3">
                <label for="description_courte" class="form-label text-secondary">Description courte :</label>
                <textarea class="form-control" id="description_courte" name="description_courte" rows="3" required><?= $description_courte ?></textarea>
            </div>

            <!-- Description longue -->
            <div class="mb-3">
                <label for="description_longue" class="form-label text-secondary">Description longue :</label>
                <textarea class="form-control" id="description_longue" name="description_longue" rows="5" required><?= $description_longue ?></textarea>
            </div>

            <!-- Prix -->
            <div class="mb-3">
                <label for="prix" class="form-label text-secondary">Prix :</label>
                <input type="number" class="form-control" id="prix" value="<?= $prix ?>" name="prix" required>
            </div>

            <!-- Catégorie -->
            <div class="mb-3">
                <label for="categorie" class="form-label text-secondary">Catégorie :</label>
                <select class="form-select" id="categorie" name="categorie" required>
                    <option value="1" <?= ($categorie == 1) ? 'selected' : '' ?>>Emploi</option>
                    <option value="2" <?= ($categorie == 2) ? 'selected' : '' ?>>Véhicule</option>
                    <option value="3" <?= ($categorie == 3) ? 'selected' : '' ?>>Immobilier</option>
                    <option value="4" <?= ($categorie == 4) ? 'selected' : '' ?>>Vacances</option>
                    <option value="5" <?= ($categorie == 5) ? 'selected' : '' ?>>Multimédia</option>
                    <option value="6" <?= ($categorie == 6) ? 'selected' : '' ?>>Loisirs</option>
                    <option value="7" <?= ($categorie == 7) ? 'selected' : '' ?>>Matériel</option>
                    <option value="8" <?= ($categorie == 8) ? 'selected' : '' ?>>Services</option>
                    <option value="9" <?= ($categorie == 9) ? 'selected' : '' ?>>Maison</option>
                    <option value="10" <?= ($categorie == 10) ? 'selected' : '' ?>>Vêtements</option>
                    <option value="11" <?= ($categorie == 11) ? 'selected' : '' ?>>Autres</option>
                </select>
            </div>

            <!-- Localisation -->
            <div class="mb-3">
                <label for="pays" class="form-label text-secondary">Pays :</label>
                <input type="text" class="form-control" id="pays" value="<?= $pays ?>" name="pays" required>
            </div>

            <div class="mb-3">
                <label for="ville" class="form-label text-secondary">Ville :</label>
                <input type="text" class="form-control" id="ville" value="<?= $ville ?>" name="ville" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label text-secondary">Adresse :</label>
                <input type="text" class="form-control" id="adresse" value="<?= $adresse ?>" name="adresse" required>
            </div>
            <div class="mb-3">
                <label for="cp" class="form-label text-secondary">Code postal :</label>
                <input type="text" class="form-control" id="cp" value="<?= $cp ?>" name="cp" required>
            </div>

            <button type="submit" class="btn btn-primary">Modifier l'annonce</button>
        </form>
    </div>
<?php endif; ?>

<?php if (isset($_GET['action'])) : ?>
    <?php if ($_GET['action'] == 'delete') {
        // Préparation de la requête
        $deleteAnnonce = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce");

        // Liaison des paramètres
        $deleteAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_INT);

        // Exécution de la requête
        if ($deleteAnnonce->execute()) {
            echo "L'annonce a été supprimée avec succès.";
        } else {
            echo "Une erreur est survenue lors de la suppression.";
        }
    } ?>
<?php endif; ?>

<!-- Tableau -->
<?php $nbAnnonces = $pdo->query(" SELECT id_annonce FROM annonce ") ?>

<!-- j'utilise cette infos pour dénombrer les produits en BDD (rowCount() va me permettre d'afficher ce nombre) -->
<h1 class="mt-5 text-center text-secondary">Nombre d'annonces enregistrées : <?= $nbAnnonces->rowCount() ?></h1>
<div class="table-responsive">

    <table class="table table-hover bg-light mt-5 rounded shadow ">
        <thead>
            <tr class="table-secondary">
                <th scope="col">#</th>
                <th scope="col">Titre</th>
                <th scope="col">Description courte</th>
                <th scope="col">Description longue</th>
                <th scope="col">Prix</th>
                <th scope="col">Photo</th>
                <th scope="col">Pays</th>
                <th scope="col">Ville</th>
                <th scope="col">Adresse</th>
                <th scope="col">Code postal</th>
                <th scope="col">Membre ID</th>
                <th scope="col">Photo ID</th>
                <th scope="col">Catégorie ID</th>
                <th scope="col">Date enregistrement</th>
                <th colspan='2'>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $listeAnnonces = $pdo->query(" SELECT * FROM annonce ");

            while ($annonce = $listeAnnonces->fetch(PDO::FETCH_ASSOC)) :
            ?>
                <tr>
                    <th scope="row"><?= $annonce['id_annonce'] ?></th>
                    <td><?= $annonce['titre'] ?></td>
                    <td><?= $annonce['description_courte'] ?></td>
                    <td>
                        <span class="description-longue">
                            <?= substr($annonce['description_longue'], 0, 40) ?>
                            <span class="toggle-description" data-hidden-description="<?= htmlspecialchars($annonce['description_longue']) ?>">
                                <?= strlen($annonce['description_longue']) > 40 ? '...' : '' ?>
                            </span>
                        </span>
                    </td>
                    <td><?= $annonce['prix'] ?> €</td>
                    <td><?= $annonce['photo'] ?></td>
                    <td><?= $annonce['pays'] ?></td>
                    <td><?= $annonce['ville'] ?></td>
                    <td><?= $annonce['adresse'] ?></td>
                    <td><?= $annonce['cp'] ?></td>
                    <td><?= $annonce['membre_id'] ?></td>
                    <td><?= $annonce['photo_id'] ?></td>
                    <td><?= $annonce['categorie_id'] ?></td>
                    <td><?= $annonce['date_enregistrement'] ?></td>
                    <td class="text-center">
                        <a href="?action=update&id_annonce=<?= $annonce['id_annonce'] ?>"><i class="bi bi-pen-fill text-warning"></i></a>
                    </td>
                    <td class="text-center">

                        <a href="?action=delete&id_annonce=<?= $annonce['id_annonce'] ?>"><i class="bi bi-trash-fill text-danger"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>






<?php require_once('includeAdmin/footer.php'); ?>