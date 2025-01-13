<?php
include $_SERVER['DOCUMENT_ROOT'] . '/test/navbar.html'; ?>
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
            margin-top:40px;

        }
        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .form-group label {
            font-weight: bold;
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
    <div class="container">
        <h2 class="text-center">Gestion des Revenus et Dépenses</h2>

        <!-- Formulaire pour ajouter une transaction -->
        <div class="card">
            <div class="card-header">
                Ajouter une transaction
            </div>
            <div class="card-body">
                <form id="transactionForm" method="POST" action="submit_transaction.php">
                    <div class="form-group">
                        <label for="type_transaction">Type de Transaction</label>
                        <select class="form-control" id="type_transaction" name="type_transaction" required>
                            <option value="revenu">Revenu</option>
                            <option value="dépense">Dépense</option>
                        </select>
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
