<?php
require_once('includeAdmin/header.php');

?>

<?php


// Top 5 des membres les mieux notés
$query_top_rating = "SELECT pseudo, AVG(commentaire) as moyenne FROM membre JOIN commentaire ON membre.id_membre = commentaire.membre_id GROUP BY membre_id ORDER BY moyenne DESC LIMIT 5";
$result_top_rating = $pdo->query($query_top_rating);

// Top 5 des membres les plus actifs
$query_top_active = "SELECT pseudo, COUNT(*) as total_annonce FROM membre JOIN annonce ON membre.id_membre = annonce.membre_id GROUP BY membre_id ORDER BY total_annonce DESC LIMIT 5";
$result_top_active = $pdo->query($query_top_active);

// Top 5 des annonces les plus anciennes
$query_top_old = "SELECT titre, date_enregistrement FROM annonce ORDER BY date_enregistrement ASC LIMIT 5";
$result_top_old = $pdo->query($query_top_old);

// Top 5 des catégories contenant le plus d'annonces
$query_top_categories = "SELECT c.titre, COUNT(*) as total_annonce FROM categorie c JOIN annonce a ON c.id_categorie = a.categorie_id GROUP BY c.id_categorie ORDER BY total_annonce DESC LIMIT 5";
$result_top_categories = $pdo->query($query_top_categories);
?>

<div class="container mt-5">
<h1 class="text-center my-5">
    <div class="badge badge-primary text-wrap p-3">Statistiques</div>
</h1>

    <div class="row">
        <div class="col-md-6">
            <?php if ($result_top_rating && $result_top_rating->rowCount() > 0): ?>
                <h2>Top 5 des membres les mieux notés</h2>
                <ul class="list-group">
                    <?php while ($row = $result_top_rating->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item"><?php echo $row['pseudo']; ?> (Note moyenne : <?php echo round($row['moyenne'], 2); ?>)</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Aucun résultat pour les membres les mieux notés.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <?php if ($result_top_active && $result_top_active->rowCount() > 0): ?>
                <h2>Top 5 des membres les plus actifs</h2>
                <ul class="list-group">
                    <?php while ($row = $result_top_active->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item"><?php echo $row['pseudo']; ?> (Nombre d'annonces : <?php echo $row['total_annonce']; ?>)</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Aucun résultat pour les membres les plus actifs.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <?php if ($result_top_old && $result_top_old->rowCount() > 0): ?>
                <h2>Top 5 des annonces les plus anciennes</h2>
                <ul class="list-group">
                    <?php while ($row = $result_top_old->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item"><?php echo $row['titre']; ?> (Date d'enregistrement : <?php echo $row['date_enregistrement']; ?>)</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Aucune annonce trouvée.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <?php if ($result_top_categories && $result_top_categories->rowCount() > 0): ?>
                <h2>Top 5 des catégories contenant le plus d'annonces</h2>
                <ul class="list-group">
                    <?php while ($row = $result_top_categories->fetch(PDO::FETCH_ASSOC)): ?>
                        <li class="list-group-item"><?php echo $row['titre']; ?> (Nombre d'annonces : <?php echo $row['total_annonce']; ?>)</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Aucune catégorie trouvée.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php require_once('includeAdmin/footer.php'); ?>
