<?php
require_once('include/init.php');

if (internauteConnecte() && isset($_POST['id_annonce'], $_POST['rating'], $_POST['review']) && !empty($_POST['id_annonce']) && !empty($_POST['rating']) && !empty($_POST['review'])) {
    $id_annonce = intval($_POST['id_annonce']);
    $rating = intval($_POST['rating']);
    $review = htmlspecialchars($_POST['review']);
    $date_enregistrement = date('Y-m-d H:i:s'); // Obtenez la date et l'heure actuelles

    // Insérez la note dans la base de données
    $query = $pdo->prepare("INSERT INTO note (membre_id1, membre_id2, note, avis, date_enregistrement) VALUES (?, ?, ?, ?, ?)");
    $success = $query->execute([$_SESSION['membre']['id_membre'], $id_annonce, $rating, $review, $date_enregistrement]);

    if ($success) {
        // Rediriger vers la page d'annonce avec un message de succès
        header("Location: annonce.php?id=$id_annonce&success=1");
        exit();
    } else {
        // En cas d'échec de l'insertion, rediriger avec un message d'erreur
        header("Location: annonce.php?id=$id_annonce&error=1");
        exit();
    }
} else {
    // Rediriger si les données nécessaires sont manquantes
    if(isset($_POST['id_annonce'])) {
        $id_annonce = intval($_POST['id_annonce']);
        header("Location: annonce.php?id=$id_annonce&error=2");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>
