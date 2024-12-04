<?php
header('Content-Type: application/json');
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Format d'email invalide."]);
        exit;
    }
    
    // Préparation de la requête SQL pour récupérer l'utilisateur par son email
    $stmt = $conn->prepare("SELECT * FROM inscrit WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            // Authentification réussie
            echo json_encode(["success" => true, "message" => "Connexion réussie !"]);
        } else {
            // Mot de passe incorrect
            echo json_encode(["success" => false, "message" => "Mot de passe incorrect."]);
        }
    } else {
        // L'utilisateur n'existe pas
        echo json_encode(["success" => false, "message" => "Email non trouvé."]);
    }
} else {
    // Méthode non autorisée
    echo json_encode(["success" => false, "message" => "Méthode non autorisée."]);
}

?>
