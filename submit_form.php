<?php
// Informations de connexion à la base de données
$servername = "localhost"; // ou 127.0.0.1
$username = "root"; // Utilisateur par défaut sur XAMPP
$password = ""; // Mot de passe par défaut est vide
$dbname = "mini_projet"; // Nom de la base de données dans phpMyAdmin

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
// Récupération des données du formulaire
$email = $_POST['email'];
$mp = $_POST['mp'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['adresse'];
$message = $_POST['message'];

// Préparer et exécuter la requête SQL
$sql = "INSERT INTO personne (mp, nom, prenom, adresse, email, message) 
        VALUES ('$mp', '$nom', '$prenom', '$adresse', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Inscription réussie !";
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

// Fermeture de la connexion
$conn->close();
?>
