CREATE TABLE test_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    previous_month_revenues DECIMAL(10, 2) NOT NULL,
    rolling_revenues_3m DECIMAL(10, 2) NOT NULL,
    previous_month_expenses DECIMAL(10, 2) NOT NULL,
    rolling_expenses_3m DECIMAL(10, 2) NOT NULL,
    previous_net_balance DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES inscrit`(id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


INSERT INTO test_data (user_id, previous_month_revenues, rolling_revenues_3m, previous_month_expenses, rolling_expenses_3m, previous_net_balance)
SELECT 
    user_id,
    SUM(CASE WHEN type_transaction = 'revenu' AND DATE_FORMAT(date_transaction, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m') THEN montant ELSE 0 END) AS previous_month_revenues,
    AVG(CASE WHEN type_transaction = 'revenu' AND date_transaction BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH) AND LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)) THEN montant ELSE 0 END) AS rolling_revenues_3m,
    SUM(CASE WHEN type_transaction = 'dépense' AND DATE_FORMAT(date_transaction, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m') THEN montant ELSE 0 END) AS previous_month_expenses,
    AVG(CASE WHEN type_transaction = 'dépense' AND date_transaction BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH) AND LAST_DAY(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)) THEN montant ELSE 0 END) AS rolling_expenses_3m,
    (
        SUM(CASE WHEN type_transaction = 'revenu' AND DATE_FORMAT(date_transaction, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m') THEN montant ELSE 0 END) -
        SUM(CASE WHEN type_transaction = 'dépense' AND DATE_FORMAT(date_transaction, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m') THEN montant ELSE 0 END)
    ) AS previous_net_balance
FROM transaction
GROUP BY user_id;


ALTER TABLE test_data
CHANGE COLUMN previous_month_expenses Previous_Month_Expenses FLOAT,
CHANGE COLUMN rolling_expenses_3m Rolling_Expenses_3M FLOAT,
CHANGE COLUMN previous_net_balance Previous_NetBalance FLOAT,
CHANGE COLUMN previous_month_revenues Previous_Month_Revenues FLOAT,
CHANGE COLUMN rolling_revenues_3m Rolling_Revenues_3M FLOAT;