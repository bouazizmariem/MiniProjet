<?php

require_once 'config.php'; 
// Vérification de la connexion
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données: ' . $conn->connect_error]));
}

// Vérifier si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer les données du formulaire
    $nom = $_POST['goalName'];
    $montantCible = $_POST['goalAmount'];
    $montantActuel = $_POST['currentAmount'];
    $progression = ($montantActuel / $montantCible) * 100;

    // Préparer la requête SQL
    $stmt = $conn->prepare("INSERT INTO objectifs (nom, montant_cible, montant_actuel, progression) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sddd", $nom, $montantCible, $montantActuel, $progression); // "sddd" correspond à string, double, double, double

    // Exécuter la requête
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Objectif ajouté avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'objectif: ' . $stmt->error]);
    }

    // Fermer la requête préparée
    $stmt->close();
}

// Fermer la connexion à la base de données
$conn->close();
?>
