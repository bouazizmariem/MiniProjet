CREATE TABLE transaction (
  id int(11) NOT NULL,
  type_transaction enum('revenu','dépense') NOT NULL,
  montant decimal(10,2) NOT NULL,
  description varchar(255) DEFAULT NULL,
  
  date_transaction timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE transaction
ADD COLUMN categorie VARCHAR(255) NOT NULL;
ALTER TABLE transaction
ADD user_id INT NOT NULL,
ADD FOREIGN KEY (user_id) REFERENCES inscrit(id) ON DELETE CASCADE ON UPDATE CASCADE;