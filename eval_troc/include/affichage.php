<?php
// Inclure le fichier d'initialisation
require_once('include/init.php');

// Variable pour afficher les annonces
$afficheAnnonce = "";

// Récupération des catégories depuis la base de données
$queryCategories = $pdo->query("SELECT * FROM categorie");
$categories = $queryCategories->fetchAll(PDO::FETCH_ASSOC);

// Récupération des membres depuis la base de données
$queryMembres = $pdo->query("SELECT * FROM membre");
$membres = $queryMembres->fetchAll(PDO::FETCH_ASSOC);

// Récupération des villes depuis la base de données
$queryVilles = $pdo->query("SELECT DISTINCT ville FROM annonce"); // Sélectionner les villes distinctes des annonces
$villes = $queryVilles->fetchAll(PDO::FETCH_COLUMN);

// Construction de la requête SQL de base pour récupérer toutes les annonces
$query = "SELECT annonce.*, photo.photo1 FROM annonce INNER JOIN photo ON photo_id = photo.id_photo";

// Filtrage des annonces en fonction des paramètres GET

$params = array(); // Tableau des paramètres de la requête préparée
$where = ""; // Clause WHERE pour filtrer la requête

// Filtrage par catégorie
if (!empty($_GET['categorie'])) {
    $where .= " WHERE categorie_id = :categorie";
    $params[':categorie'] = $_GET['categorie'];
}

// Filtrage par membre
if (!empty($_GET['membre'])) {
    $where .= !empty($where) ? " AND " : " WHERE ";
    $where .= " membre_id = :membre";
    $params[':membre'] = $_GET['membre'];
}

// Filtrage par prix
if (!empty($_GET['prix'])) {
    $where .= !empty($where) ? " AND " : " WHERE ";
    $where .= " prix <= :prix";
    $params[':prix'] = $_GET['prix'];
}

// Filtrage par ville
if (!empty($_GET['ville'])) {
    $where .= !empty($where) ? " AND " : " WHERE ";
    $where .= " ville = :ville";
    $params[':ville'] = $_GET['ville'];
}

$query .= $where;

// Ajout du tri
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'date_desc'; // Définir le tri par défaut comme 'date_desc'

switch ($tri) {
    case 'prix_asc':
        $query .= " ORDER BY prix ASC";
        break;
    case 'prix_desc':
        $query .= " ORDER BY prix DESC";
        break;
    case 'date_asc':
        $query .= " ORDER BY date_enregistrement ASC";
        break;
    case 'date_desc':
        $query .= " ORDER BY date_enregistrement DESC";
        break;
    case 'meilleurs_vendeurs':
        $query .= " ORDER BY membre_id DESC";
        break;
    default:
        $query .= " ORDER BY date_enregistrement DESC"; // Par défaut, trier par date de la plus récente à la plus ancienne
        break;
}

// Nombre total d'annonces
$queryTotalAnnonces = $pdo->prepare("SELECT COUNT(*) AS total FROM annonce" . $where);
$queryTotalAnnonces->execute($params);
$resultTotalAnnonces = $queryTotalAnnonces->fetch(PDO::FETCH_ASSOC);
$totalAnnonces = $resultTotalAnnonces['total'];

// Calcul du nombre total de pages
$parPage = 8; // Nombre d'annonces par page
$totalPages = ceil($totalAnnonces / $parPage);

// Gestion de la pagination
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $pagePrincipale = (int) strip_tags($_GET['page']);
} else {
    $pagePrincipale = 1;
}

$firstAnnonce = ($pagePrincipale - 1) * $parPage;

// Construction de la requête SQL pour récupérer les annonces de la page actuelle
$query .= " LIMIT :limit OFFSET :offset";

$queryAnnonces = $pdo->prepare($query);
$queryAnnonces->bindParam(':limit', $parPage, PDO::PARAM_INT);
$queryAnnonces->bindParam(':offset', $firstAnnonce, PDO::PARAM_INT);
foreach ($params as $key => &$value) {
    $queryAnnonces->bindParam($key, $value);
}
$queryAnnonces->execute();
$annonces = $queryAnnonces->fetchAll(PDO::FETCH_ASSOC);
?>

<body class="bg-light">
    <div class="container-fluid">
        <!-- Barre de tri en haut -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="tri">Trier par :</label>
                        <select class="form-control" id="tri" name="tri" onchange="this.form.submit()">
                            <option value="prix_asc" <?php if ($tri == 'prix_asc') echo 'selected'; ?>>Prix (du moins cher au plus cher)</option>
                            <option value="prix_desc" <?php if ($tri == 'prix_desc') echo 'selected'; ?>>Prix (du plus cher au moins cher)</option>
                            <option value="date_asc" <?php if ($tri == 'date_asc') echo 'selected'; ?>>Date (de la plus ancienne à la plus récente)</option>
                            <option value="date_desc" <?php if ($tri == 'date_desc') echo 'selected'; ?>>Date (de la plus récente à la plus ancienne)</option>
                            <option value="meilleurs_vendeurs" <?php if ($tri == 'meilleurs_vendeurs') echo 'selected'; ?>>Meilleurs vendeurs</option>
                        </select>
                    </div>
                </form>
                <!-- Affichage du nombre de résultats -->
                <p><?php echo $totalAnnonces; ?> résultat(s) trouvé(s)</p>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="row mt-4">
            <!-- Filtres à gauche -->
            <div class="col-md-3">
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select class="form-control" id="categorie" name="categorie">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?= $categorie['id_categorie']; ?>" <?php if (isset($_GET['categorie']) && $_GET['categorie'] == $categorie['id_categorie']) echo 'selected'; ?>>
                                    <?= $categorie['titre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="membre">Membre</label>
                        <select class="form-control" id="membre" name="membre">
                            <option value="">Tous les membres</option>
                            <?php foreach ($membres as $membre) : ?>
                                <option value="<?= $membre['id_membre']; ?>" <?php if (isset($_GET['membre']) && $_GET['membre'] == $membre['id_membre']) echo 'selected'; ?>>
                                    <?= $membre['pseudo']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <select class="form-control" id="ville" name="ville">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $ville) : ?>
                                <option value="<?= $ville; ?>" <?php if (isset($_GET['ville']) && $_GET['ville'] == $ville) echo 'selected'; ?>>
                                    <?= $ville; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prix">Prix</label>
                        <input type="range" class="form-control-range" id="prix" name="prix" min="0" max="50000" oninput="updatePrice(this.value)">
                        <output for="prix" id="prixOutput"><?= $_GET['prix'] ?? '200' ?>€</output>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </form>
            </div>


            <!-- Annonces à droite -->
            <div class="col-md-9">
                <div class="row">
                    <?php foreach ($annonces as $annonce) : ?>
                        <?php
                        // Récupération des informations de la catégorie de cette annonce
                        $queryCategorie = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie = ?");
                        $queryCategorie->execute([$annonce['categorie_id']]);
                        $categorie = $queryCategorie->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <div class="col-md-12 mb-4">
                            <div class="row align-items-center border rounded p-3">
                                <div class="col-md-12">
                                    <h6><?= $categorie['titre']; ?></h6>
                                </div>
                                <div class="col-md-4">
                                    <!-- Envelopper l'image dans un lien -->
                                    <a href="afficheAnnonce.php?id=<?= $annonce['id_annonce'] ?>">
                                        <img src="<?= $annonce['photo1']; ?>" class="img-fluid" alt="Image de l'annonce">
                                    </a>
                                </div>
                                <div class="col-md-8">
                                    <h5><?= $annonce['titre']; ?></h5>
                                    <p><?= $annonce['description_courte']; ?></p>
                                    <ul class="list-unstyled">
                                        <li>Prix: <?= $annonce['prix']; ?>€</li>
                                    </ul>
                                    <!-- Lien "Voir plus" -->
                                    <a href="afficheAnnonce.php?id=<?= $annonce['id_annonce'] ?>" class="btn btn-primary">Voir plus</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($i == $pagePrincipale) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php if (isset($_GET['tri'])) echo '&tri=' . $_GET['tri']; ?><?php if (isset($_GET['categorie'])) echo '&categorie=' . $_GET['categorie']; ?><?php if (isset($_GET['membre'])) echo '&membre=' . $_GET['membre']; ?><?php if (isset($_GET['prix'])) echo '&prix=' . $_GET['prix']; ?><?php if (isset($_GET['ville'])) echo '&ville=' . $_GET['ville']; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</body>

<?php require_once('include/footer.php'); ?>