<?php
// Inclure la barre de navigation
include $_SERVER['DOCUMENT_ROOT'] . '/MiniProjet/navbar.html';

// Inclusion du fichier de configuration pour la connexion DB
require_once 'config.php'; 

// Vérification de la connexion
if (!$conn) {
    die("La connexion à la base de données a échoué.");
}

// Traitement du formulaire pour ajouter un objectif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nom = $_POST['goalName'];
    $montantCible = $_POST['goalAmount'];
    $montantActuel = $_POST['currentAmount'];
    $progression = ($montantActuel / $montantCible) * 100;

    $stmt = $conn->prepare("INSERT INTO objectifs (nom, montant_cible, montant_actuel, progression) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sddd", $nom, $montantCible, $montantActuel, $progression); 

    if ($stmt->execute()) {
        echo "<script>alert('Objectif ajouté avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout de l\'objectif');</script>";
    }

    $stmt->close();
}

// Traitement de la modification d'un objectif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['goalId'];
    $nom = $_POST['goalName'];
    $montantCible = $_POST['goalAmount'];
    $montantActuel = $_POST['currentAmount'];
    $progression = ($montantActuel / $montantCible) * 100;

    $stmt = $conn->prepare("UPDATE objectifs SET nom = ?, montant_cible = ?, montant_actuel = ?, progression = ? WHERE id = ?");
    $stmt->bind_param("sdddi", $nom, $montantCible, $montantActuel, $progression, $id); 

    if ($stmt->execute()) {
        echo "<script>alert('Objectif modifié avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de la modification de l\'objectif');</script>";
    }

    $stmt->close();
}

// Traitement de la suppression d'un objectif
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM objectifs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Objectif supprimé avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression de l\'objectif');</script>";
    }

    $stmt->close();
}

// Requête SQL pour récupérer les objectifs
$sql = "SELECT id, nom, montant_cible, montant_actuel, (montant_actuel / montant_cible) * 100 AS progression FROM objectifs";
$result = $conn->query($sql);

$objectifs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $objectifs[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Objectifs Financiers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 {
            color: #343a40;
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            transition: width 0.4s ease;
        }
        .list-group-item {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .list-group-item h5 {
            color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 800px;
            margin-top:40px;

        }
         /* Ajustement pour le contenu avec la barre latérale */
         body {
            margin: 0;
            padding: 0;
            display: flex;
        }
        #content {
            display: flex;
            padding: 0px;
            margin-left:200px;

        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Objectifs Financiers</h2>
        <!-- Formulaire d'ajout d'objectif -->
        <div class="card p-4 mb-4">
            <h4 class="mb-3">Définir un nouvel objectif financier</h4>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label for="goalName" class="form-label">Nom de l'objectif</label>
                    <input type="text" class="form-control" name="goalName" id="goalName" placeholder="Ex : Économiser pour les vacances" required>
                </div>
                <div class="mb-3">
                    <label for="goalAmount" class="form-label">Montant cible (€)</label>
                    <input type="number" class="form-control" name="goalAmount" id="goalAmount" placeholder="Ex : 1000" required>
                </div>
                <div class="mb-3">
                    <label for="currentAmount" class="form-label">Montant actuel (€)</label>
                    <input type="number" class="form-control" name="currentAmount" id="currentAmount" placeholder="Ex : 200" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ajouter l'objectif</button>
            </form>
        </div>

        <!-- Liste des objectifs -->
        <div class="card p-4">
            <h4 class="mb-3">Vos objectifs</h4>
            <ul class="list-group">
                <?php if (!empty($objectifs)): ?>
                    <?php foreach ($objectifs as $goal): ?>
                        <li class="list-group-item mb-3">
                            <h5><?php echo $goal['nom']; ?></h5>
                            <p>Montant actuel: €<?php echo number_format($goal['montant_actuel'], 2); ?> / €<?php echo number_format($goal['montant_cible'], 2); ?></p>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $goal['progression']; ?>%" aria-valuenow="<?php echo $goal['progression']; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo number_format($goal['progression'], 1); ?>%</div>
                            </div>
                            <!-- Bouton de suppression -->
                            <a href="?action=delete&id=<?php echo $goal['id']; ?>" class="btn btn-danger btn-sm mt-2">Supprimer</a>
                            <!-- Bouton de modification pour afficher le modal -->
                            <button class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $goal['id']; ?>">Modifier</button>
                        </li>

                        <!-- Modal de modification -->
                        <div class="modal fade" id="editModal<?php echo $goal['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Modifier l'objectif</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="goalId" value="<?php echo $goal['id']; ?>">
                                            <div class="mb-3">
                                                <label for="goalName" class="form-label">Nom de l'objectif</label>
                                                <input type="text" class="form-control" name="goalName" value="<?php echo $goal['nom']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="goalAmount" class="form-label">Montant cible (€)</label>
                                                <input type="number" class="form-control" name="goalAmount" value="<?php echo $goal['montant_cible']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="currentAmount" class="form-label">Montant actuel (€)</label>
                                                <input type="number" class="form-control" name="currentAmount" value="<?php echo $goal['montant_actuel']; ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">Sauvegarder les modifications</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">Aucun objectif trouvé.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
