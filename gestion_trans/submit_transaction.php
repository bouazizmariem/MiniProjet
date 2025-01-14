<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    die("Erreur : L'utilisateur n'est pas connecté ou l'ID utilisateur est invalide.");
}

// Informations de connexion à la base de données
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "budget"; 

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Récupérer l'ID utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Récupérer les données du formulaire
$type_transaction = isset($_POST['type_transaction']) ? $_POST['type_transaction'] : '';
$categorie = isset($_POST['categorie']) ? $_POST['categorie'] : ''; 
$montant = isset($_POST['montant']) ? $_POST['montant'] : 0;
$description = isset($_POST['description']) ? $_POST['description'] : '';

// Préparer la requête avec l'ID utilisateur
$stmt = $conn->prepare("INSERT INTO transaction (type_transaction, categorie, montant, description, user_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssisi", $type_transaction, $categorie, $montant, $description, $user_id);

// Exécuter la requête
if ($stmt->execute()) {
    // Si la transaction est ajoutée avec succès, rediriger vers la page affiche_trans.php
    header("Location: affiche_trans.php");
    exit(); 
} else {
    echo "Erreur : " . $stmt->error;
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
