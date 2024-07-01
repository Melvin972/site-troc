<?php
require_once('include/init.php');
require_once('include/fonctions.php');

// Assurez-vous que la session est démarrée

// Vérifiez si l'identifiant de l'annonce est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Assainir l'identifiant de l'annonce
    $id_annonce = intval($_GET['id']);

    // Requête pour récupérer les détails de l'annonce
    $queryAnnonce = $pdo->prepare("SELECT * FROM annonce WHERE id_annonce = ?");
    $queryAnnonce->execute([$id_annonce]);
    $annonce = $queryAnnonce->fetch(PDO::FETCH_ASSOC);

    // Requête pour récupérer les détails du vendeur avec le numéro de téléphone
    $queryVendeur = $pdo->prepare("SELECT *, telephone FROM membre WHERE id_membre = ?");
    $queryVendeur->execute([$annonce['membre_id']]);
    $vendeur = $queryVendeur->fetch(PDO::FETCH_ASSOC);

    if ($annonce && $vendeur) {
        // Afficher les détails de l'annonce
        require_once('include/header.php');
?>
        <div class="container">
            <!-- Votre code pour afficher les détails de l'annonce -->

            <div class="row justify-content-end mt-3">
                <div class="col-auto">
                    <!-- Bouton pour contacter le vendeur -->
                    <button class="btn btn-success" data-toggle="modal" data-target="#contactModal">Contacter <?= $vendeur['prenom']; ?></button>
                </div>
            </div>

            <div class="row justify-content-center mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <img src="<?= $annonce['photo']; ?>" alt="<?= $annonce['titre']; ?>" class="card-img-top img-fluid">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $annonce['titre']; ?></h5>
                            <p class="card-text"><?= $annonce['description_courte']; ?></p>
                            <p class="card-text"><?= $annonce['description_longue']; ?></p>
                            <div class="d-flex align-items-center mb-2">
                                <img src="img/logo_position.png" alt="Position" style="width: 20px; height: 20px;">
                                <p class="mb-0"><?= $annonce['adresse'] . ', ' . $annonce['ville'] . ', ' . $annonce['cp']; ?></p>
                            </div>
                            <p class="card-text">Prix: <?= $annonce['prix']; ?>€</p>
                            <!-- Afficher d'autres détails de l'annonce selon vos besoins -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lien pour déposer un commentaire ou une note -->
            <div class="row justify-content-start mt-3">
                <div class="col-12">
                    <a href="#" id="commentOrRateLink">Déposer un commentaire ou une note</a>
                </div>
            </div>

            <!-- Modal pour contacter le vendeur -->
            <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contactModalLabel">Contacter <?= $vendeur['prenom']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="traitement_contact.php" method="post">
                                <div class="form-group">
                                    <label for="senderName">Votre nom:</label>
                                    <input type="text" class="form-control" id="senderName" name="senderName" required>
                                </div>
                                <div class="form-group">
                                    <label for="senderEmail">Votre adresse email:</label>
                                    <input type="email" class="form-control" id="senderEmail" name="senderEmail" required>
                                </div>
                                <div class="form-group">
                                    <label for="sellerPhone">Numéro de téléphone du vendeur:</label>
                                    <input type="text" class="form-control" id="sellerPhone" name="sellerPhone" value="<?= $vendeur['telephone']; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message:</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal pour déposer un commentaire ou une note -->
            <div class="modal fade" id="commentOrRateModal" tabindex="-1" role="dialog" aria-labelledby="commentOrRateModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentOrRateModalLabel">Déposer un commentaire ou une note</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs" id="commentOrRateTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="rate-tab" data-toggle="tab" href="#rate" role="tab" aria-controls="rate" aria-selected="true">Attribuer une note</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="comment-tab" data-toggle="tab" href="#comment" role="tab" aria-controls="comment" aria-selected="false">Déposer un commentaire</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="commentOrRateTabContent">
                                <div class="tab-pane fade show active" id="rate" role="tabpanel" aria-labelledby="rate-tab">
                                    <!-- Formulaire pour attribuer une note -->
                                    <form action="attribuer_note.php" method="post">
                                        <input type="hidden" name="id_annonce" value="<?= $id_annonce ?>">
                                        <div class="form-group">
                                            <label for="rating">Votre note:</label>
                                            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="review">Avis sur la relation avec le vendeur:</label>
                                            <textarea class="form-control" id="review" name="review" rows="4" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Attribuer</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="comment" role="tabpanel" aria-labelledby="comment-tab">
                                    <!-- Formulaire pour déposer un commentaire -->
                                    <form action="ajouter_commentaire.php" method="post">
                                        <input type="hidden" name="id_annonce" value="<?= $id_annonce ?>">
                                        <div class="form-group">
                                            <label for="commentaire">Votre commentaire:</label>
                                            <textarea class="form-control" id="commentaire" name="commentaire" rows="4" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Poster</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Afficher les commentaires existants -->
            <?php
            $queryCommentaires = $pdo->prepare("SELECT c.*, m.prenom FROM commentaire c INNER JOIN membre m ON c.membre_id = m.id_membre WHERE c.annonce_id = ?");
            $queryCommentaires->execute([$id_annonce]);
            $commentaires = $queryCommentaires->fetchAll(PDO::FETCH_ASSOC);

            if ($commentaires) {
                echo '<div class="row justify-content-center mt-3">';
                echo '<div class="col-md-6">';
                echo '<h5>Commentaires</h5>';
                echo '<ul class="list-group">';
                foreach ($commentaires as $commentaire) {
                    echo '<li class="list-group-item">';
                    echo '<strong>' . $commentaire['prenom'] . '</strong>: ' . $commentaire['commentaire'];
                    echo '<span class="comment-date"> - ' . ' Publié le ' . strftime('%d %B %Y à %H:%M', strtotime($commentaire['date_enregistrement'])) . '</span>'; // Formatage de la date
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <script>
            // Script pour afficher la modal de commentaire ou note
            $(document).ready(function() {
                $('#commentOrRateLink').click(function() {
                    $('#commentOrRateModal').modal('show');
                });
            });
        </script>

<?php
        // Requête pour récupérer les autres annonces basées sur des critères similaires
        $queryAutresAnnonces = $pdo->prepare("SELECT * FROM annonce WHERE id_annonce != ? AND categorie_id = ? LIMIT 3");
        $queryAutresAnnonces->execute([$id_annonce, $annonce['categorie_id']]);
        $autresAnnonces = $queryAutresAnnonces->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les autres annonces
        if ($autresAnnonces) {
            echo '<div class="container mt-5">';
            echo '<h2 class="h4">Annonces similaires</h2>';
            echo '<div class="row">';
            foreach ($autresAnnonces as $autreAnnonce) {
                echo '<div class="col-md-3 col-sm-6 col-12">'; // Modifier la classe pour rendre les cards plus larges
                echo '<div class="card mb-3">';
                echo '<img src="' . $autreAnnonce['photo'] . '" class="card-img-top img-fluid" alt="' . $autreAnnonce['titre'] . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $autreAnnonce['titre'] . '</h5>';
                echo '<p class="card-text">' . $autreAnnonce['description_courte'] . '</p>';
                echo '<a href="afficheAnnonce.php?id=' . $autreAnnonce['id_annonce'] . '" class="btn btn-primary btn-sm">Voir l\'annonce</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }

        // Requête pour récupérer les autres annonces du même membre
        $queryAutresAnnoncesMembre = $pdo->prepare("SELECT * FROM annonce WHERE membre_id = ? AND id_annonce != ?");
        $queryAutresAnnoncesMembre->execute([$annonce['membre_id'], $id_annonce]);
        $autresAnnoncesMembre = $queryAutresAnnoncesMembre->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer le prénom du membre
        $queryPseudoMembre = $pdo->prepare("SELECT pseudo FROM membre WHERE id_membre = ?");
        $queryPseudoMembre->execute([$annonce['membre_id']]);
        $pseudoMembre = $queryPseudoMembre->fetch(PDO::FETCH_COLUMN);

        // Afficher les autres annonces du membre
        if ($autresAnnoncesMembre) {
            echo '<div class="container mt-5">';
            echo '<h2 class="h4">Autres annonces de ' . $pseudoMembre . '</h2>';
            echo '<div class="row">';
            foreach ($autresAnnoncesMembre as $autreAnnonceMembre) {
                echo '<div class="col-md-3 col-sm-6 col-12">'; // Modifier la classe pour rendre les cards plus larges
                echo '<div class="card mb-3">';
                echo '<img src="' . $autreAnnonceMembre['photo'] . '" class="card-img-top img-fluid" alt="' . $autreAnnonceMembre['titre'] . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $autreAnnonceMembre['titre'] . '</h5>';
                echo '<p class="card-text">' . $autreAnnonceMembre['description_courte'] . '</p>';
                echo '<a href="afficheAnnonce.php?id=' . $autreAnnonceMembre['id_annonce'] . '" class="btn btn-primary btn-sm">Voir l\'annonce</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
    } else {
        // Gérer le cas où aucune annonce correspondante n'est trouvée
        echo 'Aucune annonce trouvée pour cet identifiant.';
    }
} else {
    // Gérer le cas où aucun identifiant d'annonce n'est passé dans l'URL
    echo 'Identifiant d\'annonce manquant dans l\'URL.';
}

// Bouton pour retourner à la page d'annonces
echo '<div class="container mt-3">';
echo '<div class="row justify-content-start">';
echo '<div class="col-12">';
echo '<div class="card bg-light">';
echo '<div class="card-body text-center">';
echo '<a href="index.php" class="btn btn-primary">Retour à la page d\'annonces</a>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

require_once('include/footer.php');
?>
