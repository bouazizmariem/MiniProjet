<?php
// Informations de connexion à la base de données
$servername = "localhost"; // ou 127.0.0.1
$username = "root"; // Utilisateur par défaut sur XAMPP
$password = ""; // Mot de passe par défaut est vide
$dbname = "budget"; // Nom de la base de données dans phpMyAdmin

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$type_transaction = isset($_POST['type_transaction']) ? $_POST['type_transaction'] : '';
$montant = isset($_POST['montant']) ? $_POST['montant'] : 0;
$description = isset($_POST['description']) ? $_POST['description'] : '';

// Préparer et exécuter la requête SQL
$sql = "INSERT INTO transaction (type_transaction, montant, description) 
        VALUES ('$type_transaction', '$montant', '$description')";

if ($conn->query($sql) === TRUE) {
    // Si la transaction est ajoutée avec succès, rediriger vers la page affiche_trans.php
    header("Location: affiche_trans.php");
    exit(); // Assurez-vous d'arrêter l'exécution après la redirection
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

// Fermer la connexion
$conn->close();
?>
