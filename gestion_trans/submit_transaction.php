<?php
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

// Récupérer les données du formulaire
$type_transaction = isset($_POST['type_transaction']) ? $_POST['type_transaction'] : '';
$categorie = isset($_POST['categorie']) ? $_POST['categorie'] : ''; 
$montant = isset($_POST['montant']) ? $_POST['montant'] : 0;
$description = isset($_POST['description']) ? $_POST['description'] : '';

// Préparer la requête
$stmt = $conn->prepare("INSERT INTO transaction (type_transaction, categorie, montant, description) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $type_transaction, $categorie, $montant, $description);

// Exécuter la requête
if ($stmt->execute()) {
  // Si la transaction est ajoutée avec succès, rediriger vers la page affiche_trans.php
  header("Location: affiche_trans.php");
  exit(); 
} else {
  echo "Erreur : " . $sql . "<br>" . $conn->error;
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
