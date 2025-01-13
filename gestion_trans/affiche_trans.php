<?php
// Récupérer les paramètres de filtrage
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';
include $_SERVER['DOCUMENT_ROOT'] . '/test/navbar.html';
?>

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
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            margin-left: 300px;
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

        .transaction-list {
            margin-top: 20px;
            text-align: center;
        }

        .transaction-item {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 90%; /* Centrer en réduisant la largeur */
            text-align: left;
            transition: transform 0.3s ease;
        }

        .transaction-item:hover {
            transform: scale(1.02);
        }

        .transaction-info {
            font-size: 1.1rem;
            margin-left: 15px;
        }

        .revenu .transaction-icon {
            background-color: #ff6347; /* Tomate */
            color: white;
        }

        .depense .transaction-icon {
            background-color: #8a2be2; /* Violet */
            color: white;
        }

        .transaction-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .pagination .page-link {
            background-color: #8a2be2; /* Violet clair */
            color: white;
        }

        .pagination .page-link:hover {
            background-color: #ff6347; /* Tomate */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Historique des Transactions</h2>

        <!-- Filtrage des transactions -->
        <div class="filters d-flex justify-content-between">
            <form class="form-inline" method="GET" action="">
                <label for="typeTransaction" class="mr-2">Type de transaction :</label>
                <select id="typeTransaction" name="type" class="form-control mr-2">
                    <option value="">Tous</option>
                    <option value="revenu" <?php echo $typeFilter == 'revenu' ? 'selected' : ''; ?>>Revenu</option>
                    <option value="depense" <?php echo $typeFilter == 'depense' ? 'selected' : ''; ?>>Dépense</option>
                </select>
                <button type="submit" class="btn btn-custom">Filtrer</button>
            </form>
        </div>

        <!-- Liste des transactions -->
        <div class="transaction-list" id="transactionCards">
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

            // Afficher la liste des transactions
            while ($row = $result->fetch_assoc()) {
                $cardClass = ($row['type_transaction'] == 'revenu') ? 'revenu' : 'depense';
                $montant = ($row['type_transaction'] == 'revenu') ? '+' . number_format($row['montant'], 2) : '-' . number_format($row['montant'], 2);
                $icon = ($row['type_transaction'] == 'revenu') ? 'fa fa-dollar-sign' : 'fa fa-credit-card'; // Icône
                $categorie = htmlspecialchars($row['categorie']); // Sécuriser la catégorie

                echo "<div class='transaction-item $cardClass'>
                        <div class='transaction-icon'>
                            <i class='$icon'></i>
                        </div>
                        <div class='transaction-info'>
                            <strong>Date:</strong> {$row['date_transaction']}<br>
                            <strong>Catégorie:</strong> $categorie<br>
                            <strong>Montant:</strong> $montant
                        </div>
                    </div>";
            }

            // Pagination
            $totalSql = "SELECT COUNT(*) AS total FROM transaction";
            if ($typeFilter) {
                $totalSql .= " WHERE type_transaction = '$typeFilter'";
            }
            $totalResult = $conn->query($totalSql);
            $total = $totalResult->fetch_assoc()['total'];
            $totalPages = ceil($total / $limit);

            echo "<ul class='pagination'>";
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&type=$typeFilter'>Précédent</a></li>";
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class='page-item'><a class='page-link' href='?page=$i&type=$typeFilter'>$i</a></li>";
            }

            if ($page < $totalPages) {
                echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&type=$typeFilter'>Suivant</a></li>";
            }
            echo "</ul>";

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
