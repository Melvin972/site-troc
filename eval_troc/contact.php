<?php
require_once('include/header.php');
?>

<div class="container">
        <h1 class="mt-5 mb-4">Nous contacter</h1>
        <div class="row">
            <div class="col-md-6">
                <form action="process_contact.php" method="post">
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message :</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.760776188815!2d2.2954119!3d48.8437016!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e67016f69f4243%3A0x5f2dbe5c7a43f572!2s25%20Rue%20Mademoiselle%2C%2075015%20Paris!5e0!3m2!1sfr!2sfr!4v1708075403920!5m2!1sfr!2sfr" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <p class="mt-4">Pour nous contacter par téléphone, veuillez composer le : <strong>+33 1 23 45 67 89</strong>.</p>
    </div>

<?php
require_once('include/footer.php');
