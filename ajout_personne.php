<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire d'inscription</title>
  
  <!-- Lien vers Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- CSS personnalisé pour la mise en forme des messages d'erreur -->
  <style>
    .form-container {
      max-width: 400px;
      margin: 0 auto;
      padding: 2rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #f9f9f9;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }
    .error-message {
      color: red;
      font-size: 0.85em;
    }
    .password-wrapper {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="form-container">
      <h2 class="text-center mb-4">Formulaire d'inscription</h2>
<form id="registrationForm" action="submit_form.php" method="POST" novalidate>
        
        <!-- Champ Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
          <p class="error-message" id="emailError"></p>
        </div>
        
        <!-- Champ Mot de passe avec icône d'affichage -->
        <div class="form-group">
          <label for="mp">Mot de passe</label>
          <div class="password-wrapper">
            <input type="password" class="form-control" id="mp" name="mp" placeholder="Entrez votre mot de passe" required>
            <span class="toggle-password" onclick="togglePasswordVisibility()">
              <i id="passwordIcon" class="fas fa-eye"></i>
            </span>
          </div>
          <p class="error-message" id="mpError"></p>
        </div>

        <!-- Champ Confirmation du mot de passe -->
        <div class="form-group">
          <label for="confirmMp">Confirmation du mot de passe</label>
          <input type="password" class="form-control" id="confirmMp" placeholder="Confirmez votre mot de passe" required>
          <p class="error-message" id="confirmMpError"></p>
        </div>

        <!-- Champs Nom, Prénom, Adresse, Message -->
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
          <p class="error-message" id="nomError"></p>
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
          <p class="error-message" id="prenomError"></p>
        </div>
        <div class="form-group">
          <label for="adresse">Adresse</label>
          <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Entrez votre adresse" required>
          <p class="error-message" id="adresseError"></p>
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea class="form-control" id="message" name="message" rows="3" placeholder="Entrez votre message"></textarea>
          <p class="error-message" id="messageError"></p>
        </div>
        
        <!-- Boutons de validation et de réinitialisation -->
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">Valider</button>
          <button type="reset" class="btn btn-secondary">Supprimer</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Modèle des expressions régulières
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;

    // Récupérer les champs
    const emailInput = document.getElementById('email');
    const mpInput = document.getElementById('mp');
    const confirmMpInput = document.getElementById('confirmMp');

    // Fonction pour afficher/masquer le mot de passe
    function togglePasswordVisibility() {
      const passwordIcon = document.getElementById('passwordIcon');
      if (mpInput.type === 'password') {
        mpInput.type = 'text';
        passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        mpInput.type = 'password';
        passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }

    // Affichage des erreurs en temps réel
    emailInput.addEventListener('input', () => {
      document.getElementById('emailError').textContent = emailPattern.test(emailInput.value) ? '' : "L'email n'est pas valide.";
    });
    mpInput.addEventListener('input', () => {
      document.getElementById('mpError').textContent = passwordPattern.test(mpInput.value) 
        ? '' 
        : "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un symbole spécial.";
    });
    confirmMpInput.addEventListener('input', () => {
      document.getElementById('confirmMpError').textContent = confirmMpInput.value === mpInput.value 
        ? '' 
        : "Les mots de passe ne correspondent pas.";
    });

    // Validation finale à la soumission
    document.getElementById('registrationForm').addEventListener('submit', (event) => {
      event.preventDefault();

      // Vérifier les champs avant de soumettre
      const isEmailValid = emailPattern.test(emailInput.value);
      const isPasswordValid = passwordPattern.test(mpInput.value);
      const isPasswordConfirmed = confirmMpInput.value === mpInput.value;

      if (!isEmailValid) emailError.textContent = "L'email n'est pas valide.";
      if (!isPasswordValid) mpError.textContent = "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un symbole spécial.";
      if (!isPasswordConfirmed) confirmMpError.textContent = "Les mots de passe ne correspondent pas.";

      if (isEmailValid && isPasswordValid && isPasswordConfirmed) {
        alert("Formulaire soumis avec succès !");
        event.target.submit(); // Soumettre le formulaire
      }
    });
  </script>

  <!-- Scripts Bootstrap et FontAwesome -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
