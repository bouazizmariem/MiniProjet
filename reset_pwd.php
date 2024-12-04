<?php
// Connexion à la base de données
require 'config.php';

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$email = $_POST['email'];
$token = $_POST['token'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Vérifier si les mots de passe correspondent
if ($new_password !== $confirm_password) {
    die("Les mots de passe ne correspondent pas.");
}

// Vérifier la validité du token
$sql = "SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Le lien de réinitialisation est invalide ou a expiré.");
}

$row = $result->fetch_assoc();

// Hacher le nouveau mot de passe avant de le sauvegarder
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Mettre à jour le mot de passe dans la table des utilisateurs
$sql_update = "UPDATE users SET password = ? WHERE email = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("ss", $hashed_password, $email);
$stmt_update->execute();

// Supprimer le token de réinitialisation de la table
$sql_delete = "DELETE FROM password_resets WHERE email = ? AND token = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("ss", $email, $token);
$stmt_delete->execute();

echo "Votre mot de passe a été réinitialisé avec succès.";
?>
