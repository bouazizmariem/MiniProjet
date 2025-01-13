<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisation des Transactions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1000px;
            margin-top: 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card-header {
            font-size: 1.2rem;
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            text-align: right;
        }

        .revenu {
            border-left: 5px solid #ff7f50; /* Orange */
        }

        .depense {
            border-left: 5px solid #8a2be2; /* Violet */
        }

        .transaction-info {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .filters select,
        .filters button {
            margin-right: 10px;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            margin-bottom: 30px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-deck .card {
            width: 30%;
            margin-bottom: 20px;
        }

        .card-deck {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Boutons Previous et Next */
        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-link {
            background-color: #8a2be2; /* Violet clair */
            color: white;
        }

        .pagination .page-link:hover {
            background-color: #ff7f50; /* Orange clair */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Historique des Transactions</h2>

        <!-- Filtrage des transactions -->
        <div class="filters d-flex justify-content-between">
            <form class="form-inline">
                <label for="typeTransaction" class="mr-2">Type de transaction :</label>
                <select id="typeTransaction" class="form-control mr-2">
                    <option value="">Tous</option>
                    <option value="revenu">Revenu</option>
                    <option value="depense">Dépense</option>
                </select>

                <button type="button" class="btn btn-custom" onclick="applyFilter()">Filtrer</button>
            </form>
        </div>
        <!-- Les transactions seront affichées ici sous forme de cartes -->
        <div class="row" id="transactionCards">
            <!-- Les cartes des transactions seront insérées ici via PHP -->
        </div>
        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <!-- Les liens de pagination seront insérés ici via PHP -->
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialisation du graphique
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Revenus', 'Dépenses'],
                datasets: [{
                    label: 'Transactions',
                    data: [0, 0], // Les données seront mises à jour via PHP
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true,
                    }
                }
            }
        });

        function applyFilter() {
            const type = document.getElementById('typeTransaction').value;
            const url = window.location.href.split('?')[0]; // URL sans paramètres
            window.location.href = type ? `${url}?type=${type}` : url; // Rechargement avec filtre
        }
    </script>
</body>
</html>

<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budget";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les paramètres de filtrage
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';

// Pagination
$limit = 6; // Nombre de transactions par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Récupérer les transactions avec le filtre appliqué
$sql = "SELECT * FROM transaction";
if ($typeFilter) {
    $sql .= " WHERE type_transaction = '$typeFilter'";
}
$sql .= " ORDER BY date_transaction DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Compter le nombre total de transactions
$totalSql = "SELECT COUNT(*) AS total FROM transaction";
if ($typeFilter) {
    $totalSql .= " WHERE type_transaction = '$typeFilter'";
}
$totalResult = $conn->query($totalSql);
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Afficher les cartes des transactions
while($row = $result->fetch_assoc()) {
    $cardClass = ($row['type_transaction'] == 'revenu') ? 'revenu' : 'depense';
    $montant = ($row['type_transaction'] == 'revenu') ? '+' . number_format($row['montant'], 2) : '-' . number_format($row['montant'], 2);
    $icon = ($row['type_transaction'] == 'revenu') ? 'fa fa-dollar-sign' : 'fa fa-credit-card'; // Icône

    echo "<div class='col-md-4'>
            <div class='card $cardClass'>
                <div class='card-header'>{$row['type_transaction']}</div>
                <div class='card-body'>
                    <div class='transaction-info'>
                        <i class='transaction-icon $icon'></i>
                        <strong>Date:</strong> {$row['date_transaction']}
                    </div>
                    <div class='transaction-info'>
                        <strong>Montant:</strong> $montant
                    </div>
                </div>
            </div>
        </div>";
}

// Pagination
echo "<ul class='pagination'>";
if ($page > 1) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Précédent</a></li>";
}

for ($i = 1; $i <= $totalPages; $i++) {
    echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
}

if ($page < $totalPages) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Suivant</a></li>";
}
echo "</ul>";

$conn->close();
?>
