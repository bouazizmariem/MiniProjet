<?php
include $_SERVER['DOCUMENT_ROOT'] . '/MINIPROJET/navbar.html';
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
        document.getElementById("goalForm").addEventListener("submit", function(event) {
            event.preventDefault();  // Empêche le rechargement de la page

            // Récupérer les données du formulaire
            const goalName = document.getElementById("goalName").value;
            const goalAmount = document.getElementById("goalAmount").value;
            const currentAmount = document.getElementById("currentAmount").value;

            // Vérifier que les champs sont bien remplis
            if (goalName && goalAmount && currentAmount) {
                // Créer l'objet FormData pour envoyer les données
                const formData = new FormData();
                formData.append("goalName", goalName);
                formData.append("goalAmount", goalAmount);
                formData.append("currentAmount", currentAmount);

                // Envoyer les données au fichier PHP via AJAX
                fetch('AjoutObjectif.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);  // Afficher un message de succès
                        // Optionnel: Réinitialiser le formulaire
                        document.getElementById("goalForm").reset();
                    } else {
                        alert(data.message);  // Afficher un message d'erreur
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert("Une erreur s'est produite lors de l'ajout de l'objectif.");
                });
            } else {
                alert("Tous les champs sont requis.");
            }
        });

        window.onload = function () {
            fetch('afficher_obj.php', {
                method: 'GET' // Requête GET vers afficher_obj.php
            })
            .then(response => response.json())  // On récupère les données JSON
            .then(data => {
                console.log(data);  // Debug, afficher les données dans la console
                const goalItems = document.getElementById("goalItems");
                const updateFormContainer = document.getElementById("updateFormContainer");

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(goal => {
                        const goalName = goal.nom;
                        const goalAmount = parseFloat(goal.montant_cible);
                        const currentAmount = parseFloat(goal.montant_actuel);
                        let progress = goal.progression;
                        progress = !isNaN(progress) ? parseFloat(progress) : 0;

                        // Vérification si 'progress' est bien un nombre avant d'utiliser toFixed()
                        if (!isNaN(progress)) {
                            // Création de l'élément de liste pour chaque objectif
                            const goalItem = document.createElement("li");
                            goalItem.classList.add("list-group-item", "mb-3");

                            goalItem.innerHTML = `
                                <h5>${goalName}</h5>
                                <p>Montant actuel: €${currentAmount.toFixed(2)} / €${goalAmount.toFixed(2)}</p>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ${progress}%" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">${progress.toFixed(1)}%</div>
                                </div>
                                <button class="btn btn-warning btn-sm" onclick="editGoal(${goal.id}, '${goal.nom}', ${goal.montant_cible}, ${goal.montant_actuel})">Modifier</button>
                            `;
                            goalItems.appendChild(goalItem);  // Ajout de l'objectif à la liste
                        } else {
                            // Si 'progress' est invalide, afficher une barre de progression à 0%
                            const goalItem = document.createElement("li");
                            goalItem.classList.add("list-group-item", "mb-3");

                            goalItem.innerHTML = `
                                <h5>${goalName}</h5>
                                <p>Montant actuel: €${currentAmount.toFixed(2)} / €${goalAmount.toFixed(2)}</p>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                                <button class="btn btn-warning btn-sm" onclick="editGoal(${goal.id}, '${goal.nom}', ${goal.montant_cible}, ${goal.montant_actuel})">Modifier</button>
                            `;
                            goalItems.appendChild(goalItem);  // Ajout de l'objectif à la liste
                        }
                    });
                } else {
                    goalItems.innerHTML = '<li class="list-group-item">Aucun objectif trouvé.</li>';
                }
            })
            .catch(error => console.error('Erreur:', error));
        };

        // Afficher le formulaire de modification avec les valeurs de l'objectif
        function editGoal(id, nom, montantCible, montantActuel) {
            document.getElementById('goalIdToUpdate').value = id;
            document.getElementById('updateGoalName').value = nom;
            document.getElementById('updateGoalAmount').value = montantCible;
            document.getElementById('updateCurrentAmount').value = montantActuel;

            // Afficher le formulaire de mise à jour
            document.getElementById('updateFormContainer').style.display = 'block';
        }

        // Écouteur d'événements pour soumettre le formulaire de mise à jour
        document.getElementById('updateGoalForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Empêcher la soumission par défaut

            const goalId = document.getElementById('goalIdToUpdate').value;
            const goalName = document.getElementById('updateGoalName').value;
            const goalAmount = document.getElementById('updateGoalAmount').value;
            const currentAmount = document.getElementById('updateCurrentAmount').value;

            // Faire une requête POST pour mettre à jour l'objectif
            fetch('update_goal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    goalId: goalId,
                    goalName: goalName,
                    goalAmount: goalAmount,
                    currentAmount: currentAmount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Recharger la liste des objectifs après la mise à jour
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Erreur:', error));
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>