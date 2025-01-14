<?php
header('Content-Type: application/json');
require 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Hachage du mot de passe pour plus de sécurité
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Préparation de la requête SQL
    $stmt = $conn->prepare("INSERT INTO inscrit (nom, prenom, email, password) VALUES (? ,?,?,?)");
    $stmt->bind_param("ssss", $nom, $prenom, $email, $password);


    // Exécution de la requête
    if ($stmt->execute()) {
        header("Location: ../navbar.html");
    } 
}
?>
