<?php
// Vérification de la validité du lien (si un token et un email sont passés dans l'URL)
if (!isset($_GET['email']) || !isset($_GET['token'])) {
    die("Lien invalide ou expiré.");
}

$email = $_GET['email'];
$token = $_GET['token'];

// Vous devez vérifier si le token et l'email sont valides dans votre base de données ici.
// Exemple : on suppose qu'une table 'password_resets' contient les tokens de réinitialisation.

// Connexion à la base de données (adaptez selon votre configuration)
require 'config.php';

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Rechercher le token dans la base de données
$sql = "SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Le lien de réinitialisation est invalide ou a expiré.");
}

$stmt->close();
?>

