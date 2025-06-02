<?php
/* Vérification si le formulaire a été envoyé */
class UserController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traitement du formulaire
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Ici on simulera un enregistrement plus tard en base de données
            echo "<p> Merci pour votre inscription, $firstname $lastname !</p>";
        }

        // Affichage du formulaire
        require './views/register.php';
    }
}
