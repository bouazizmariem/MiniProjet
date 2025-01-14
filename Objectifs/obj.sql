CREATE TABLE objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    montant_cible DECIMAL(10, 2) NOT NULL,
    montant_actuel DECIMAL(10, 2) NOT NULL,
    progression DECIMAL(5, 2) NOT NULL
);