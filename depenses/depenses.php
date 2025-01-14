<?php
include $_SERVER['DOCUMENT_ROOT'] . '/MINIPROJET/navbar.html'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories des Dépenses</title>
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
            border: 2px solid #007bff;
            border-radius: 50%;
            color: #007bff;
            font-size: 32px;
            transition: background-color 0.3s, color 0.3s;
            cursor: pointer;
        }
        .icon-category:hover {
            background-color: #007bff;
            color: #fff;
        }
        .category-label {
            display: none;
            position: absolute;
            top: 110%; /* Positionner le label en dessous de l'icône */
            left: 50%;
            transform: translateX(-50%);
            background-color: #007bff;
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
        <h1>Gestion des Dépenses par Catégorie</h1>
        <div class="d-flex justify-content-center flex-wrap mt-4">
            <!-- Icônes des catégories avec leurs labels -->
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Voyage','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Voyage">
                        <i class="bi-airplane"></i>
                    </div>
                    <div class="category-label">Voyage</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Cadeau','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Cadeau">
                        <i class="bi-gift"></i>
                    </div>
                    <div class="category-label">Cadeau</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Anniversaire','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Anniversaire">
                        <i class="bi-cake"></i>
                    </div>
                    <div class="category-label">Anniversaire</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Transport','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Transport">
                        <i class="bi-car-front"></i>
                    </div>
                    <div class="category-label">Transport</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Frais d\'éducation','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Frais d'éducation">
                        <i class="bi-mortarboard"></i>
                    </div>
                    <div class="category-label">Frais d'éducation</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Soins de santé','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Soins de santé">
                        <i class="bi-hospital"></i>
                    </div>
                    <div class="category-label">Soins de santé</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Maison','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Maison">
                        <i class="bi-house"></i>
                    </div>
                    <div class="category-label">Maison</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Shopping','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Shopping">
                        <i class="bi-cart3"></i>
                    </div>
                    <div class="category-label">Shopping</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Facture d\'eau','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Facture d'eau">
                        <i class="bi-journal-check"></i>
                    </div>
                    <div class="category-label">Facture d'eau</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Banque','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Banque">
                        <i class="bi-bank"></i>
                    </div>
                    <div class="category-label">Banque</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Facture d\'électricité','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Facture d\'électricité">
                        <i class="bi-fuel-pump"></i>
                    </div>
                    <div class="category-label">Facture d'électricité</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Frais d\'Internet','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Frais d'Internet">
                        <i class="bi-globe2"></i>
                    </div>
                    <div class="category-label">Frais d'Internet</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('PhotoShoot','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="PhotoShoot">
                        <i class="bi-camera2"></i>
                    </div>
                    <div class="category-label">PhotoShoot</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Médicaments','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Médicaments">
                        <i class="bi-capsule"></i>
                    </div>
                    <div class="category-label">Médicaments</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Achats en ligne','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Achats en ligne">
                        <i class="bi-coin"></i>
                    </div>
                    <div class="category-label">Achats en ligne</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Cinéma','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Cinéma">
                        <i class="bi-collection-play"></i>
                    </div>
                    <div class="category-label">Cinéma</div>
                </a>
            </div>
            <div class="icon-wrapper">
                <a href="#" onclick="redirectToForm('Divertissement','dépense')" class="icon-category-link">
                    <div class="icon-category" data-categorie="Divertissement">
                        <i class="bi-suitcase2"></i>
                    </div>
                    <div class="category-label">Divertissement</div>
                </a>
            </div>
        </div>
    </div>

    <script>
        function redirectToForm(categoryName, type) {
            // Créez l'URL de redirection en ajoutant le nom de la catégorie et du type comme paramètres
            var url = '/MINIPROJET/gestion_trans/ajout_trans.php?categorie=' + encodeURIComponent(categoryName) + '&type=' + encodeURIComponent(type);
            // Effectuez la redirection vers l'URL formée
            window.location.href = url;
        }
    </script>
</body>
</html>
