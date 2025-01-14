<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php'; // Assurez-vous que la configuration de votre base de données est incluse

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données: ' . $conn->connect_error]));
}

// Vérifier si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['id'], $_POST['goalName'], $_POST['goalAmount'], $_POST['currentAmount'])) {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
        exit;
    }
    $goalId = $_POST['id'];
    $nom = $_POST['goalName'];
    $montantCible = $_POST['goalAmount'];
    $montantActuel = $_POST['currentAmount'];
    $progression = ($montantActuel / $montantCible) * 100;

    // Préparer la requête SQL pour mettre à jour l'objectif
    $stmt = $conn->prepare("UPDATE objectifs SET nom = ?, montant_cible = ?, montant_actuel = ?, progression = ? WHERE id = ?");
    $stmt->bind_param("sdddi", $nom, $montantCible, $montantActuel, $progression, $goalId); // "sdddi" correspond à string, double, double, double, int

    // Exécuter la requête
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Objectif mis à jour avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'objectif: ' . $stmt->error]);
    }

    // Fermer la requête préparée
    $stmt->close();
}

// Fermer la connexion à la base de données
$conn->close();
?>
