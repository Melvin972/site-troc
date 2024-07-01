<?php
require_once('includeAdmin/header.php');

// Traitement de la modification de catégorie
if (isset($_POST['modifier_categorie'])) {
    $id_categorie = $_POST['id_categorie'];
    $titre = $_POST['titre'];
    $motscles = $_POST['motscles'];

    // Exécutez votre requête de mise à jour ici
    $requete = $pdo->prepare("UPDATE categorie SET titre = ?, motscles = ? WHERE id_categorie = ?");
    $requete->execute([$titre, $motscles, $id_categorie]);
}

// Traitement de la suppression de catégorie
if (isset($_GET['supprimer_categorie'])) {
    $id_categorie = $_GET['supprimer_categorie'];

    // Exécutez votre requête de suppression ici
    $suppression = $pdo->prepare("DELETE FROM categorie WHERE id_categorie = ?");
    $suppression->execute([$id_categorie]);
}

// Récupération des catégories pour affichage
$rechercheCategories = $pdo->query("SELECT id_categorie, titre, motscles FROM categorie");

?>

<!-- Affichage des catégories -->
<div class="container mt-5">
    <h1 class="text-center my-5">
        <div class="badge badge-primary text-wrap p-3">Gestion des catégories</div>
    </h1>

    <div class="blockquote alert alert-dismissible fade show shadow border border-primary rounded" role="alert">
        <p>Gérez ici les catégories de votre base de données</p>
        <p>Vous pouvez ajouter, modifier ou supprimer une catégorie</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php $nbCategories = $pdo->query(" SELECT id_categorie FROM categorie ") ?>
    <h2 class="mt-5 text-center text-secondary">Nombre de catégorie en base de données: <?= $nbCategories->rowCount() ?></h2>

    <table class="table table-hover bg-light mt-5 rounded shadow ">
        <thead>
            <tr class="table-secondary">
                <th>ID</th>
                <th>Titre</th>
                <th>Mots-clés</th>
                <th colspan='2'>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($categorie = $rechercheCategories->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= $categorie['id_categorie'] ?></td>
                    <td><?= $categorie['titre'] ?></td>
                    <td><?= $categorie['motscles'] ?></td>
                    <td class="text-center">
                        <!-- Formulaire de modification de catégorie -->
                        <form action="" method="post">
                            <input type="hidden" name="id_categorie" value="<?= $categorie['id_categorie'] ?>">
                            <input type="hidden" name="titre" value="<?= $categorie['titre'] ?>">
                            <input type="hidden" name="motscles" value="<?= $categorie['motscles'] ?>">
                            <button type="submit" class="btn btn-link" name="modifier_categorie"><i class="bi bi-pen-fill text-warning"></i></button>
                        </form>
                    </td>
                    <td class="text-center">
                        <!-- Formulaire de suppression de catégorie -->
                        <form action="" method="get">
                            <input type="hidden" name="supprimer_categorie" value="<?= $categorie['id_categorie'] ?>">
                            <button type="submit" class="btn btn-link" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');"><i class="bi bi-trash-fill text-danger"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('includeAdmin/footer.php'); ?>