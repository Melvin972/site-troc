<?php
require_once('include/init.php');
require_once('include/fonctions.php');

if (internauteConnecte() && isset($_POST['id_annonce'], $_POST['commentaire']) && !empty($_POST['id_annonce']) && !empty($_POST['commentaire'])) {
    $id_annonce = intval($_POST['id_annonce']);
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $date_enregistrement = date('Y-m-d H:i:s'); // Obtenez la date et l'heure actuelles

    // Insérez le commentaire dans la base de données
    $query = $pdo->prepare("INSERT INTO commentaire (membre_id, annonce_id, commentaire, date_enregistrement) VALUES (?, ?, ?, ?)");
    $query->execute([$_SESSION['membre']['id_membre'], $id_annonce, $commentaire, $date_enregistrement]);
}

// Rediriger vers la page d'annonce
header("Location: index.php");
exit();
?>
