<?php
require_once('include/init.php');
require_once('include/header.php');

if (!internauteConnecte()) {
    echo "Erreur: Utilisateur non connecté";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $statut = htmlspecialchars(trim($_POST['statut']));

    $idMembre = $_SESSION['membre']['id_membre']; 

    $query = "UPDATE membre SET nom = :nom, prenom = :prenom, email = :email, statut = :statut WHERE id_membre = :idMembre";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':statut', $statut, PDO::PARAM_STR); 
    $stmt->bindParam(':idMembre', $idMembre, PDO::PARAM_INT);

    // Exécutez la requête
    if ($stmt->execute()) {
        
        // Déconnexion de l'utilisateur
        unset($_SESSION['membre']);
        session_destroy();

        // Affichage du message de mise à jour réussie et du bouton de connexion
        echo '<div class="alert alert-success" role="alert">
        Mise à jour réussie ! Vous avez été déconnecté. Veuillez vous reconnecter !
        <a href="connexion.php" class="btn btn-primary">Se reconnecter</a>
    </div>';        exit();
    } else {
        // Erreur lors de la mise à jour
        echo "Erreur lors de la mise à jour des informations. Veuillez réessayer.";
        exit();
    }
} else {
   
    echo "Erreur: Formulaire non soumis";
    exit();
}
