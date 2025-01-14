<?php
session_start(); 

// Connexion à la base de données
require 'C:\xampp\htdocs\MiniProjet\user\config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Récupération de l'email
    $newPassword = trim($_POST['password']); // Récupération du nouveau mot de passe

    // Vérifier si les champs sont remplis
    if (!empty($email) && !empty($newPassword)) {
        // Hasher le mot de passe pour plus de sécurité
        //$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Vérifier si l'email existe
        $stmt = $conn->prepare("SELECT * FROM inscrit WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mettre à jour le mot de passe
            $updateStmt = $conn->prepare("UPDATE inscrit SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $newPassword, $email);

            if ($updateStmt->execute()) {

                header("Location: ../navbar.html");
            } else {
                $messageErreur = "Une erreur est survenue lors de la mise à jour.";
            }
        } else {
            $messageErreur = "L'email n'existe pas dans la base de données.";
        }
    } else {
        $messageErreur = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Erreurs</title>
<style>
    /* Style général de la modale */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
        backdrop-filter: blur(5px); /* Ajout d'un flou subtil */
        animation: fadeIn 0.3s ease-in-out; /* Animation douce à l'ouverture */
    }

    /* Contenu de la modale */
    .modal-content {
        background: #ffffff; /* Fond blanc pur */
        margin: 10% auto;
        padding: 20px 30px;
        max-width: 450px; /* Taille contrôlée pour une meilleure lisibilité */
        border-radius: 12px; /* Coins légèrement arrondis */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15); /* Ombre subtile */
        text-align: center;
        position: relative;
        animation: slideDown 0.4s ease-in-out; /* Animation d'entrée */
    }

    /* Bouton de fermeture */
    .close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #f5f5f5; /* Fond clair et discret */
        border: none;
        color: #555; /* Couleur neutre */
        font-size: 18px;
        width: 32px;
        height: 32px;
        border-radius: 50%; /* Bouton circulaire */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .close:hover {
        background: #eaeaea; /* Légère variation au survol */
        color: #333;
        transform: scale(1.1); /* Effet de zoom subtil */
    }

    /* Texte à l'intérieur de la modale */
    .modal-content p {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Police moderne */
        font-size: 16px;
        color: #333; /* Texte sombre pour la lisibilité */
        line-height: 1.5;
        margin-bottom: 20px;
    }

    /* Bouton d'action dans la modale */
    .modal-content button {
        background: #007bff; /* Bleu professionnel */
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modal-content button:hover {
        background: #0056b3; /* Variation au survol */
        transform: translateY(-2px); /* Légère élévation */
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>


</head>
<body>
    <?php if (isset($messageErreur)): ?>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p><?= htmlspecialchars($messageErreur) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Affiche la modale si elle existe
        window.onload = function () {
            const modal = document.getElementById("myModal");
            if (modal) {
                modal.style.display = "block";
            }

            // Fermer la modale
            const closeBtn = document.querySelector(".close");
            if (closeBtn) {
                closeBtn.onclick = function () {
                    modal.style.display = "none";
                };
            }

            // Fermer la modale si l'utilisateur clique en dehors
            window.onclick = function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            };
        };
    </script>
</body>
</html>
