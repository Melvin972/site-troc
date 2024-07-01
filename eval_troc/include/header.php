<?php
require_once('include/init.php');

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- favicon -->
  <link rel="icon" type="image/jpg" href="img/troc_logo.svg" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>



  <title>TROC / ANNONCES</title>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
      <a class="navbar-brand" href="<?= URL ?>">
        <img src="<?= URL ?>img/troc_logo.svg" alt="Logo">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?= URL ?>">TROC / ANNONCES</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL ?>qui_sommes_nous.php">Qui sommes-nous ?</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= URL ?>contact.php">Contact</a>
          </li>
        </ul>

        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
          <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Rechercher</button>
        </form>

        <ul class="navbar-nav ml-auto align-items-center">
          <li class="nav-item">
            <a href="<?= URL ?>annonce.php" class="btn btn-danger btn-sm">Publier une annonce</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Espace membre
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <?php if (internauteConnecte()) { ?>
                <a class="dropdown-item" href="<?= URL ?>connexion.php"><?= $_SESSION['membre']['pseudo'] ?></a>
                <a class="dropdown-item" href="<?= URL ?>deconnexion.php">Déconnexion</a>
              <?php } else { ?>
                <a class="dropdown-item" href="<?= URL ?>connexion.php">Connexion</a>
                <a class="dropdown-item" href="<?= URL ?>inscription.php">Inscription</a>
              <?php } ?>
            </div>
          </li>

          <li class="nav-item">
            <?php if (internauteConnecteAdmin()) { ?>
              <a class="nav-link" href="admin/index.php">
                <button type="button" class="btn btn-outline-primary btn-sm">Admin</button>
              </a>
            <?php } ?>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="container">

    <!-- Modal -->
    <div class="modal fade" id="connexionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel"><img src="<?= URL ?>img/troc_logo.svg"> TROC / CONNEXION</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">

            <form name="connexion" method="POST" action="">
              <div class="row justify-content-around">
                <div class="col-md-4 mt-4">
                  <label class="form-label" for="pseudo">
                    <div class="badge badge-dark text-wrap">Pseudo</div>
                  </label>
                  <input class="form-control btn btn-outline-primary" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo">
                </div>
              </div>

              <div class="row justify-content-around">
                <div class="col-md-6 mt-4">
                  <label class="form-label" for="mdp">
                    <div class="badge badge-dark text-wrap">Mot de passe</div>
                  </label>
                  <input class="form-control btn btn-outline-primary" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">
                </div>
              </div>

              <div class="row justify-content-center">
                <button type="submit" name="connexion" class="btn btn-lg btn-outline-primary mt-3">Connexion</button>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          </div>
        </div>
      </div>
    </div>