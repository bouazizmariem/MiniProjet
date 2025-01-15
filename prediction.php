<?php
session_start();
require 'C:\xampp\htdocs\MiniProjet\user\config.php';
include 'navbar.html'; 

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo '
    
        <style>
            
            .error-container {
                text-align: center;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border: 1px solid #e0e0e0;
            }
            .error-container h1 {
                font-size: 24px;
                color: #d9534f;
                margin-bottom: 10px;
            }
            .error-container p {
                font-size: 18px;
                color: #6c757d;
            }
        </style>

    <body>
        <div class="error-container">
            <h1>Erreur</h1>
            <p>L\'utilisateur n\'est pas connecté ou l\'ID utilisateur est invalide.</p>
        </div>
    </body>
    </html>
    ';
    exit;
}

// Si l'utilisateur est connecté, continuer l'exécution du script
echo "Bienvenue, utilisateur connecté !";
$id = $_SESSION['user_id'];  // Récupérer l'ID utilisateur de la session
$sql = "SELECT prenom FROM inscrit WHERE id = '$id' ";
$result = $conn->query($sql);

// Variables pour stocker les prédictions
$predicted_revenue = null;
$predicted_expense = null;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Faire une requête POST à l'API FastAPI pour obtenir les prédictions
    $url = "http://127.0.0.1:8000/get_predictions";  // URL de l'API FastAPI
    
    // Initialiser cURL
    $ch = curl_init();
    
    // Définir les options cURL
    curl_setopt($ch, CURLOPT_URL, $url);  // URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Retourner la réponse
    curl_setopt($ch, CURLOPT_POST, 1);  // Méthode POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['user_id' => $user_id]));  // Données à envoyer en JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',  // Indiquer que c'est une requête JSON
    ]);

    // Exécuter la requête
    $response = curl_exec($ch);
    
    // Gérer les erreurs cURL
    if (curl_errno($ch)) {
        die("Erreur cURL : " . curl_error($ch));
    }

    curl_close($ch);
    error_log("Response from FastAPI: " . $response);

    // Décoder la réponse JSON
    $data = json_decode($response, true);

    // Vérifier si les données sont valides et afficher les prédictions
    if ($data && isset($data['predicted_revenue']) && isset($data['predicted_expense'])) {
        $predicted_revenue = $data['predicted_revenue'];
        $predicted_expense = $data['predicted_expense'];
    } else {
        $predicted_revenue = 'Erreur';
        $predicted_expense = 'Erreur';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prédictions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
    max-width: 800px;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: auto;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .predictions {
            margin-top: 30px;
            text-align: center;
        }

        .result {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .info-paragraph {
            margin: 30px 0;
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        .info-paragraph a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .info-paragraph a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>


<div class="container">
 <?php  
    if ($result->num_rows > 0) {
    // Récupérer la première ligne du résultat
    $row = $result->fetch_assoc();
    // Afficher le nom de l'utilisateur
    echo "<h1>Bienvenu " . $row['prenom'] . "</h1>";
} else {
    echo "<h1>Aucun utilisateur trouvé</h1>";
}?>
    <p class="info-paragraph">
        Si vous souhaitez prédire vos dépenses et revenus à venir en fonction de vos données historiques 
        pour mieux gérer vos finances.
    </p>

    <!-- Formulaire pour envoyer l'ID utilisateur et récupérer les prédictions -->
    <form action="" method="POST">
        <div class="form-group">
            <button type="submit">cliquez ici</button>
        </div>
    </form>

    <?php if ($predicted_revenue !== null && $predicted_expense !== null): ?>
        <div class="predictions">
            <div class="result <?php echo ($predicted_revenue === 'Erreur') ? 'error' : 'success'; ?>">
                <strong>Prédiction des revenus : </strong> <?php echo $predicted_revenue; ?>
            </div>
            <div class="result <?php echo ($predicted_expense === 'Erreur') ? 'error' : 'success'; ?>">
                <strong>Prédiction des dépenses : </strong> <?php echo $predicted_expense; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
