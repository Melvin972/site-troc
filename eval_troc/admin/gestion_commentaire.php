<?php
require_once('includeAdmin/header.php');
?>

<?php
$erreur = '';
// Récupération des commentaires pour affichage
$rechercheCommentaires = $pdo->query("SELECT id_commentaire, membre_id, annonce_id, commentaire, date_enregistrement FROM commentaire");
?>

<!-- Affichage des commentaires -->
<div class="container mt-5">
    <h1 class="text-center my-5">
        <div class="badge badge-primary text-wrap p-3">Gestion des commentaires</div>
    </h1>

    <div class="blockquote alert alert-dismissible fade show shadow border border-primary rounded" role="alert">
        <p>Gérez ici les commentaires de votre base de données</p>
        <p>Vous pouvez ajouter, modifier ou supprimer un commentaire</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php $nbCommentaires = $pdo->query(" SELECT id_commentaire FROM commentaire ") ?>
    <h2 class="mt-5 text-center text-secondary">Nombre de commentaires en base de données: <?= $nbCommentaires->rowCount() ?></h2>

    <table class="table table-hover bg-light mt-5 rounded shadow ">
        <thead>
            <tr class="table-secondary">
                <th>ID</th>
                <th>ID Membre</th>
                <th>ID Annonce</th>
                <th>Commentaire</th>
                <th>Date d'enregistrement</th>
                <th colspan='2'>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($commentaire = $rechercheCommentaires->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= $commentaire['id_commentaire'] ?></td>
                    <td><?= $commentaire['membre_id'] ?></td>
                    <td><?= $commentaire['annonce_id'] ?></td>
                    <td><?= $commentaire['commentaire'] ?></td>
                    <td><?= $commentaire['date_enregistrement'] ?></td>

                    <!-- Liens pour la suppression et la modification du commentaire -->
                    <td class="text-center">
                        <a href='modifier_commentaire.php?id_commentaire=<?= $commentaire['id_commentaire'] ?>'><i class="bi bi-pen-fill text-warning"></i></a>
                    </td>
                    <td class="text-center">
                        <a href='supprimer_commentaire.php?id_commentaire=<?= $commentaire['id_commentaire'] ?>' toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('includeAdmin/footer.php'); ?>