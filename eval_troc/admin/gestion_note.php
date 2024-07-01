<?php
require_once('includeAdmin/header.php');

?>

<?php

// Récupération des notes pour affichage
$rechercheNotes = $pdo->query("SELECT * FROM note");

?>

<!-- Affichage des notes -->
<div class="container mt-5">
    <h1 class="text-center my-5">
        <div class="badge badge-primary text-wrap p-3">Gestion des notes</div>
    </h1>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="blockquote alert alert-dismissible fade show shadow border border-primary rounded" role="alert">
                <p>Gérez ici les notes de votre base de données</p>
                <p>Vous pouvez ajouter, modifier ou supprimer une note</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php $nbNotes = $pdo->query(" SELECT id_note FROM note ") ?>
            <h2 class="mt-5 text-center text-secondary">Nombre de note en base de données: <?= $nbNotes->rowCount() ?></h2>


            <div class="container">
                <table class="table table-hover bg-light mt-5 rounded shadow ">
                    <thead>
                        <tr class="table-secondary">
                            <th>ID</th>
                            <th>id membre 1</th>
                            <th>id membre 2</th>
                            <th>note</th>
                            <th>Avis</th>
                            <th>Date d'enregistrement</th>
                            <th colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($note = $rechercheNotes->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td><?= $note['id_note'] ?></td>
                                <td><?= $note['membre_id1'] ?></td>
                                <td><?= $note['membre_id2'] ?></td>
                                <td><?= $note['note'] ?></td>
                                <td><?= $note['avis'] ?></td>
                                <td><?= $note['date_enregistrement'] ?></td>
                                <td class="text-center">
                                    <a href='modifier_note.php?id_note=<?= $note['id_note'] ?>'><i class="bi bi-pen-fill text-warning"></i></a>
                                </td>
                                <td class="text-center">

                                    <a href='supprimer_note.php?id_note=<?= $note['id_note'] ?>' toggle="modal" data-target="#confirm-delete"><i class="bi bi-trash-fill text-danger"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php require_once('includeAdmin/footer.php'); ?>