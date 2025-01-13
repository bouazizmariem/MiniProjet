<?php
include $_SERVER['DOCUMENT_ROOT'] . '/test/navbar.html';
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
            <form id="goalForm">
                <div class="mb-3">
                    <label for="goalName" class="form-label">Nom de l'objectif</label>
                    <input type="text" class="form-control" id="goalName" placeholder="Ex : Économiser pour les vacances" required>
                </div>
                <div class="mb-3">
                    <label for="goalAmount" class="form-label">Montant cible (€)</label>
                    <input type="number" class="form-control" id="goalAmount" placeholder="Ex : 1000" required>
                </div>
                <div class="mb-3">
                    <label for="currentAmount" class="form-label">Montant actuel (€)</label>
                    <input type="number" class="form-control" id="currentAmount" placeholder="Ex : 200" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ajouter l'objectif</button>
            </form>
        </div>

        <!-- Liste des objectifs -->
        <div id="goalsList" class="card p-4">
            <h4 class="mb-3">Vos objectifs</h4>
            <ul class="list-group" id="goalItems">
                <!-- Les objectifs ajoutés apparaîtront ici -->
            </ul>
        </div>
    </div>

    <script>
        document.getElementById("goalForm").addEventListener("submit", function (e) {
            e.preventDefault();

            // Récupération des valeurs des champs
            const goalName = document.getElementById("goalName").value;
            const goalAmount = parseFloat(document.getElementById("goalAmount").value);
            const currentAmount = parseFloat(document.getElementById("currentAmount").value);

            // Calcul de la progression
            const progress = (currentAmount / goalAmount) * 100;

            // Création de l'élément HTML pour un nouvel objectif
            const goalItem = document.createElement("li");
            goalItem.classList.add("list-group-item", "mb-3");

            goalItem.innerHTML = `
                <h5>${goalName}</h5>
                <p>Montant actuel: €${currentAmount.toFixed(2)} / €${goalAmount.toFixed(2)}</p>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: ${progress}%" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">${progress.toFixed(1)}%</div>
                </div>
            `;

            // Ajout de l'objectif dans la liste
            document.getElementById("goalItems").appendChild(goalItem);

            // Réinitialisation du formulaire
            document.getElementById("goalForm").reset();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
