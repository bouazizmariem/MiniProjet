<?php
// Assurez-vous que ce fichier 'config.php' est bien inclus
require_once 'config.php'; 

// Vérification de la connexion
if (!$conn) {
    die("La connexion à la base de données a échoué.");
}

// Requête SQL pour récupérer les objectifs
$sql = "SELECT nom, montant_cible, montant_actuel, (montant_actuel / montant_cible) * 100 AS progression FROM objectifs";
$result = $conn->query($sql);

// Vérification des résultats
if ($result && $result->num_rows > 0) {
    $objectifs = array();

    // Récupérer les objectifs et les stocker dans le tableau
    while ($row = $result->fetch_assoc()) {
        $objectifs[] = $row;
    }

    // Retourner les objectifs en format JSON
    echo json_encode($objectifs);
} else {
    // Si aucun objectif n'est trouvé
    echo json_encode([]);
}

// Fermer la connexion à la base de données
$conn->close();
?>
