<?php
require_once('include/init.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['membre'])) {
    // Rediriger vers la page de connexion
    header('Location:' . URL . 'connexion.php');
    exit();
}

$erreur = ''; // Initialisez la variable d'erreur
$confirmationDepot = '';

if ($_POST) {
    // Traitement du formulaire seulement s'il est soumis
    $fichierInfo1 = $_FILES['photo1'];
    $uploadDirection = RACINE_SITE . 'img/';
    $uploadFichier1 = 'img/' . basename($_FILES['photo1']['name']);
    move_uploaded_file($_FILES['photo1']['tmp_name'], $uploadFichier1);

    $membre_id = $_SESSION['membre']['id_membre'];
    $id_categorie = $_POST['categorie'];

    $addPhoto = $pdo->prepare("INSERT INTO photo (photo1) VALUES (:photo1)");
    $addPhoto->bindValue(':photo1', $uploadFichier1, PDO::PARAM_STR);

    $addPhoto->execute();

    $id_photo = $pdo->lastInsertId();

    $addAnnonce = $pdo->prepare("INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre_id, photo_id, categorie_id, date_enregistrement) VALUES (:titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :membre_id, :photo_id, :categorie_id, NOW())");
    $addAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':photo', $uploadFichier1, PDO::PARAM_STR);
    $addAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':cp', $_POST['cp'], PDO::PARAM_STR);
    $addAnnonce->bindValue(':membre_id', $membre_id, PDO::PARAM_STR);
    $addAnnonce->bindValue(':photo_id', $id_photo, PDO::PARAM_INT);
    $addAnnonce->bindValue(':categorie_id', $id_categorie, PDO::PARAM_INT);
    $addAnnonce->execute();
    

    // Validation des champs après la soumission du formulaire
    if (!isset($_POST['titre']) || !preg_match('#^[a-zA-Z0-9- _.\p{L}\'àâäçéèêëîïôöùûüÿñæœ]{3,60}$#u', $_POST['titre'])) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format titre !</div>';
    
    
    }

    if (!isset($_POST['description_courte']) || strlen($_POST['description_courte']) < 8 || strlen($_POST['description_courte']) > 200) {
        $erreur .= '<div class="alert alert-danger" role="alert">Erreur format description courte !</div>';
    }

    if (!isset($_POST['description_longue']) || strlen($_POST['description_longue']) < 8 || strlen($_POST['description_longue']) > 700) {
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

    $confirmationDepot = '<div class="alert alert-success" role="alert">Votre annonce a été déposée avec succès !</div>';

}

require_once('include/header.php');
?>

<?= $confirmationDepot ?>
<?= $erreur ?>

<div class="container mt-5 bg-light p-4 rounded">
    <h2 class="mb-4 text-primary">Déposer une annonce</h2>
    <form action="" method="post" enctype="multipart/form-data">

        <!-- Titre -->
        <div class="mb-3">
            <label for="titre" class="form-label text-secondary">Titre de l'annonce :</label>
            <input type="text" class="form-control" id="titre" name="titre" required>
        </div>

        <!-- Description courte -->
        <div class="mb-3">
            <label for="description_courte" class="form-label text-secondary">Description courte :</label>
            <textarea class="form-control" id="description_courte" name="description_courte" rows="3" required></textarea>
        </div>

        <!-- Description longue -->
        <div class="mb-3">
            <label for="description_longue" class="form-label text-secondary">Description longue :</label>
            <textarea class="form-control" id="description_longue" name="description_longue" rows="5" required></textarea>
        </div>

        <!-- Prix -->
        <div class="mb-3">
            <label for="prix" class="form-label text-secondary">Prix :</label>
            <input type="number" class="form-control" id="prix" name="prix" required>
        </div>

        <!-- Catégorie -->
        <div class="mb-3">
            <label for="categorie" class="form-label text-secondary">Catégorie :</label>
            <select class="form-select" id="categorie" name="categorie" required>
                <option value="1">Emploi</option>
                <option value="2">Véhicule</option>
                <option value="3">Immobilier</option>
                <option value="4">Vacances</option>
                <option value="5">Multimédia</option>
                <option value="6">Loisirs</option>
                <option value="7">Matériel</option>
                <option value="8">Services</option>
                <option value="9">Maison</option>
                <option value="10">Vêtements</option>
                <option value="11">Autres</option>
            </select>
        </div>

        <!-- Photos -->

        <div class="mb-3">
            <label for="photo1" class="form-label text-secondary">Photo1 :</label>
            <input type="file" class="form-control-file" id="photo1" name="photo1" accept="image/*" required>
        </div>

        <!-- Localisation -->
        <div class="mb-3">
            <label for="pays" class="form-label text-secondary">Pays :</label>
            <input type="text" class="form-control" id="pays" name="pays" required>
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label text-secondary">Ville :</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label text-secondary">Adresse :</label>
            <input type="text" class="form-control" id="adresse" name="adresse" required>
        </div>
        <div class="mb-3">
            <label for="cp" class="form-label text-secondary">Code postal :</label>
            <input type="text" class="form-control" id="cp" name="cp" required>
        </div>

        <button type="submit" class="btn btn-primary">Déposer l'annonce</button>
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46Cv/ZBDL7uoL+5IcL2MW5Fbw3qd9OVpLY8DbqLvq/R" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy6p2LOHlq5i9ZZTh5fHhD6L5N6J8Zlre" crossorigin="anonymous"></script>

<?php require_once('include/footer.php');
