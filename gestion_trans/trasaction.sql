
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `type_transaction` enum('revenu','dépense') NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_transaction` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
