<?php
include '../navbar.html';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories des Revenus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .icon-wrapper {
            display: inline-block;
            position: relative;
            margin: 20px;
            text-align: center;
        }
        .icon-category {
            display: inline-block;
            width: 100px;
            height: 100px;
            line-height: 100px;
            border: 2px solid #28a745;
            border-radius: 50%;
            color: #28a745;
            font-size: 32px;
            transition: background-color 0.3s, color 0.3s;
            cursor: pointer;
        }
        .icon-category:hover {
            background-color: #28a745;
            color: #fff;
        }
        .category-label {
            display: none;
            position: absolute;
            top: 110%; /* Positionner le label en dessous de l'icône */
            left: 50%;
            transform: translateX(-50%);
            background-color: #28a745;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            white-space: nowrap;
            z-index: 10;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .icon-wrapper:hover .category-label {
            display: block;
        }
        .container {
            margin-top: 0 !important;
        }

        .add-category .btn-add-category {
            background-color: transparent;
            border: none;
            font-size: 2rem;
            color: #0d6efd;
            cursor: pointer;
            transition: color 0.3s;
        }

        .add-category .btn-add-category:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Gestion des Revenus par Catégorie</h1>
        <div class="d-flex justify-content-center flex-wrap mt-4">
            <!-- Icônes des catégories avec leurs labels -->
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Salaire','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Salaire">
                        <i class="bi-wallet2"></i>
                    </div>
                    <div class="category-label">Salaire</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Investissement','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Investissement">
                        <i class="bi-bar-chart-line"></i>
                    </div>
                    <div class="category-label">Investissement</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Vente','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Vente">
                        <i class="bi-cash-stack"></i>
                    </div>
                    <div class="category-label">Vente</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Dividendes','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Dividendes">
                        <i class="bi-coin"></i>
                    </div>
                    <div class="category-label">Dividendes</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Revenus passifs','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Revenus passifs">
                        <i class="bi-building"></i>
                    </div>
                    <div class="category-label">Revenus passifs</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Prime','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Prime">
                        <i class="bi-gift"></i>
                    </div>
                    <div class="category-label">Prime</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Remboursement','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Remboursement">
                        <i class="bi-arrow-counterclockwise"></i>
                    </div>
                    <div class="category-label">Remboursement</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Vente d\'actifs','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Vente d'actifs">
                        <i class="bi-currency-exchange"></i>
                    </div>
                    <div class="category-label">Vente d'actifs</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Remboursement d\'impôt','revenu')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Remboursement d'impôt">
                        <i class="bi-file-earmark-ruled"></i>
                    </div>
                    <div class="category-label">Remboursement d'impôt</div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function redirectToForm(categoryName, type) {
            // Créez l'URL de redirection en ajoutant le nom de la catégorie comme paramètre
            var url = '/test/gestion_trans/ajout_trans.php?categorie='+ encodeURIComponent(categoryName) + '&type=' + encodeURIComponent(type);
            // Effectuez la redirection vers l'URL formée
            window.location.href = url;
        }
    </script>
</body>
</html>
