<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données POST
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête préparée pour éviter les injections SQL
    $sql = "SELECT * FROM inscrit WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérification du mot de passe
        if ($password == $user['password']) { // Remplacez par password_verify() si les mots de passe sont hachés
            session_start();
            $_SESSION['user_id'] = $user['id'];

            // Redirection vers navbar.html si l'utilisateur est authentifié
            header("Location: ../navbar.html");
            exit();
        } else {
            // Si le mot de passe est incorrect
            echo "Mot de passe invalide.";
            header("Location: login.html");
            exit();
        }
    } else {
        // Si l'utilisateur n'est pas trouvé
        echo "Aucun utilisateur trouvé.";
        header("Location: login.html");
        exit();
    }

    // Fermer la requête préparée
    $stmt->close();
}
?>
