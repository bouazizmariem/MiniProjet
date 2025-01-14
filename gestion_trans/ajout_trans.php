<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/MiniProjet/navbar.html';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    die("Erreur : L'utilisateur n'est pas connecté ou l'ID utilisateur est invalide.");
}

// Récupérer la catégorie et le type depuis l'URL (si elles sont présentes)
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Revenus et Dépenses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 40px;
        }
        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .form-group label {
            font-weight: bold;
        }
        body {
            margin: 0;
            padding: 0;
            display: flex;
        }
        #content {
            display: flex;
            padding: 0px;
            margin-left: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Gestion des Revenus et Dépenses</h2>

        <div id="x">
            <h5>Utilisateur connecté</h5>
            <p>ID Utilisateur : <?php echo $_SESSION['user_id']; ?></p>
        </div>

        <!-- Formulaire pour ajouter une transaction -->
        <div class="card">
            <div class="card-header">
                Ajouter une transaction - Catégorie : <?php echo htmlspecialchars($categorie); ?> | Type : <?php echo htmlspecialchars($type); ?>
            </div>

            <div class="card-body">
                <form id="transactionForm" method="POST" action="submit_transaction.php">
                     <!-- Ajouter l'ID utilisateur caché -->
                    <!-- Champ caché pour l'ID utilisateur -->
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                    <div class="form-group">
                        <label for="type_transaction">Type de Transaction</label>
                        <input type="text" class="form-control" id="type" name="type_transaction" value="<?php echo htmlspecialchars($type); ?>" readonly>
                    </div>
                    <!-- Champ de catégorie pré-rempli avec la catégorie passée en URL -->
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <input type="text" class="form-control" id="categorie" name="categorie" value="<?php echo htmlspecialchars($categorie); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="montant">Montant</label>
                        <input type="number" class="form-control" id="montant" name="montant" placeholder="Entrez le montant" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description de la transaction (facultatif)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Ajouter la transaction</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
