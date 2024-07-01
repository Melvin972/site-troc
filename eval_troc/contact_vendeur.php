<?php
require_once('include/init.php');
require_once('include/fonctions.php');

// Vérifiez si l'identifiant du vendeur est passé dans l'URL
if (isset($_GET['vendeur']) && !empty($_GET['vendeur'])) {
    // Assainir l'identifiant du vendeur
    $id_vendeur = intval($_GET['vendeur']);

    // Requête pour récupérer les détails du vendeur
    $queryVendeur = $pdo->prepare("SELECT * FROM vendeur WHERE id_vendeur = ?");
    $queryVendeur->execute([$id_vendeur]);
    $vendeur = $queryVendeur->fetch(PDO::FETCH_ASSOC);

    if ($vendeur) {
        // Afficher le modal pour contacter le vendeur
        // Vous pouvez afficher le numéro de téléphone et le formulaire de contact ici
    } else {
        // Gérer le cas où aucun vendeur correspondant n'est trouvé
        echo 'Aucun vendeur trouvé pour cet identifiant.';
    }
} else {
    // Gérer le cas où aucun identifiant de vendeur n'est passé dans l'URL
    echo 'Identifiant de vendeur manquant dans l\'URL.';
}
?>