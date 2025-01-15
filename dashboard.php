<?php

session_start(); 

include 'navbar.html';
$host = 'localhost';
$dbname = 'budget';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer les dépenses par catégorie pour le mois courant
$query_depenses = "SELECT categorie, SUM(montant) AS total_depenses 
FROM transaction 
WHERE type_transaction = 'dépense' 
AND MONTH(date_transaction) = MONTH(CURRENT_DATE()) 
AND YEAR(date_transaction) = YEAR(CURRENT_DATE()) 
GROUP BY categorie";
$stmt_depenses = $pdo->query($query_depenses);

$categories_depenses = [];
$montants_depenses = [];

while ($row = $stmt_depenses->fetch(PDO::FETCH_ASSOC)) {
    $categories_depenses[] = $row['categorie'];
    $montants_depenses[] = $row['total_depenses'];
}

// Récupérer les revenus par catégorie pour le mois courant
$query_revenus = "SELECT categorie, SUM(montant) AS total_revenus 
FROM transaction 
WHERE type_transaction = 'revenu' 
AND MONTH(date_transaction) = MONTH(CURRENT_DATE()) 
AND YEAR(date_transaction) = YEAR(CURRENT_DATE()) 
GROUP BY categorie";
$stmt_revenus = $pdo->query($query_revenus);

$categories_revenus = [];
$montants_revenus = [];

while ($row = $stmt_revenus->fetch(PDO::FETCH_ASSOC)) {
    $categories_revenus[] = $row['categorie'];
    $montants_revenus[] = $row['total_revenus'];
}
$query_evolution_depenses = "SELECT MONTH(date_transaction) AS mois, SUM(montant) AS total_depenses
FROM transaction
WHERE type_transaction = 'dépense' 
AND YEAR(date_transaction) = YEAR(CURRENT_DATE()) 
GROUP BY mois ORDER BY mois ASC";
$stmt_evolution_depenses = $pdo->query($query_evolution_depenses);

$mois_depenses = [];
$montants_evolution_depenses = [];

while ($row = $stmt_evolution_depenses->fetch(PDO::FETCH_ASSOC)) {
    $mois_depenses[] = $row['mois'];
    $montants_evolution_depenses[] = $row['total_depenses'];
}
// Récupérer l'évolution des revenus sur les mois
$query_evolution_revenus = "SELECT MONTH(date_transaction) AS mois, SUM(montant) AS total_revenus
FROM transaction
WHERE type_transaction = 'revenu' 
AND YEAR(date_transaction) = YEAR(CURRENT_DATE()) 
GROUP BY mois ORDER BY mois ASC";
$stmt_evolution_revenus = $pdo->query($query_evolution_revenus);

$mois_revenus = [];
$montants_evolution_revenus = [];

while ($row = $stmt_evolution_revenus->fetch(PDO::FETCH_ASSOC)) {
    $mois_revenus[] = $row['mois'];
    $montants_evolution_revenus[] = $row['total_revenus'];
}
// Récupérer les revenus et dépenses mensuels des 6 derniers mois
$query_revenus_mensuels = "
SELECT MONTH(date_transaction) AS mois, YEAR(date_transaction) AS annee, SUM(montant) AS total_revenus
FROM transaction
WHERE type_transaction = 'revenu' 
AND date_transaction >= CURDATE() - INTERVAL 6 MONTH
GROUP BY annee, mois
ORDER BY annee DESC, mois DESC";
$stmt_revenus_mensuels = $pdo->query($query_revenus_mensuels);

$mois_revenus = [];
$montants_revenus = [];

while ($row = $stmt_revenus_mensuels->fetch(PDO::FETCH_ASSOC)) {
    $mois_revenus[] = $row['mois'] . '-' . $row['annee']; // Mois et année
    $montants_revenus[] = $row['total_revenus'];
}

// Récupérer les dépenses mensuelles des 6 derniers mois
$query_depenses_mensuelles = "
SELECT MONTH(date_transaction) AS mois, YEAR(date_transaction) AS annee, SUM(montant) AS total_depenses
FROM transaction
WHERE type_transaction = 'dépense' 
AND date_transaction >= CURDATE() - INTERVAL 6 MONTH
GROUP BY annee, mois
ORDER BY annee DESC, mois DESC";
$stmt_depenses_mensuelles = $pdo->query($query_depenses_mensuelles);

$mois_depenses = [];
$montants_depenses = [];

while ($row = $stmt_depenses_mensuelles->fetch(PDO::FETCH_ASSOC)) {
    $mois_depenses[] = $row['mois'] . '-' . $row['annee']; // Mois et année
    $montants_depenses[] = $row['total_depenses'];
}
// Récupérer les dépenses de l'année dernière
$query_depenses_annee_precedente = "
SELECT MONTH(date_transaction) AS mois, SUM(montant) AS total_depenses
FROM transaction
WHERE type_transaction = 'dépense' 
AND YEAR(date_transaction) = YEAR(CURRENT_DATE()) - 1
GROUP BY mois ORDER BY mois ASC";
$stmt_depenses_annee_precedente = $pdo->query($query_depenses_annee_precedente);

$mois_depenses_annee_precedente = [];
$montants_depenses_annee_precedente = [];

while ($row = $stmt_depenses_annee_precedente->fetch(PDO::FETCH_ASSOC)) {
    $mois_depenses_annee_precedente[] = $row['mois'];
    $montants_depenses_annee_precedente[] = $row['total_depenses'];
}

// Récupérer les revenus de l'année dernière
$query_revenus_annee_precedente = "
SELECT MONTH(date_transaction) AS mois, SUM(montant) AS total_revenus
FROM transaction
WHERE type_transaction = 'revenu' 
AND YEAR(date_transaction) = YEAR(CURRENT_DATE()) - 1
GROUP BY mois ORDER BY mois ASC";
$stmt_revenus_annee_precedente = $pdo->query($query_revenus_annee_precedente);

$mois_revenus_annee_precedente = [];
$montants_revenus_annee_precedente = [];

while ($row = $stmt_revenus_annee_precedente->fetch(PDO::FETCH_ASSOC)) {
    $mois_revenus_annee_precedente[] = $row['mois'];
    $montants_revenus_annee_precedente[] = $row['total_revenus'];
}

$query_objectifs = "SELECT nom, montant_cible, montant_actuel FROM objectifs";
$stmt_objectifs = $pdo->query($query_objectifs);

$noms_objectifs = [];
$progression_objectifs = [];

while ($row = $stmt_objectifs->fetch(PDO::FETCH_ASSOC)) {
    $noms_objectifs[] = $row['nom'];
    $progression_objectifs[] = ($row['montant_actuel'] / $row['montant_cible']) * 100; // Progression en pourcentage
}

$query_epargnes_mensuelles = "
SELECT MONTH(date_transaction) AS mois, YEAR(date_transaction) AS annee, 
(SUM(CASE WHEN type_transaction = 'revenu' THEN montant ELSE 0 END) - 
 SUM(CASE WHEN type_transaction = 'dépense' THEN montant ELSE 0 END)) AS total_epargne
FROM transaction
WHERE date_transaction >= CURDATE() - INTERVAL 6 MONTH
GROUP BY annee, mois
ORDER BY annee DESC, mois DESC";
$stmt_epargnes_mensuelles = $pdo->query($query_epargnes_mensuelles);

$mois_epargnes = [];
$montants_epargnes_mensuelles = [];

while ($row = $stmt_epargnes_mensuelles->fetch(PDO::FETCH_ASSOC)) {
    $mois_epargnes[] = $row['mois'] . '-' . $row['annee']; // Mois et année
    $montants_epargnes_mensuelles[] = $row['total_epargne'];
}

// Récupérer les épargnes annuelles
$query_epargnes_annuelles = "
SELECT YEAR(date_transaction) AS annee, 
(SUM(CASE WHEN type_transaction = 'revenu' THEN montant ELSE 0 END) - 
 SUM(CASE WHEN type_transaction = 'dépense' THEN montant ELSE 0 END)) AS total_epargne
FROM transaction
GROUP BY annee
ORDER BY annee DESC";
$stmt_epargnes_annuelles = $pdo->query($query_epargnes_annuelles);

$annees_epargnes = [];
$montants_epargnes_annuelles = [];

while ($row = $stmt_epargnes_annuelles->fetch(PDO::FETCH_ASSOC)) {
    $annees_epargnes[] = $row['annee']; // Année
    $montants_epargnes_annuelles[] = $row['total_epargne'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphiques des Dépenses et Revenus</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons: Material Design -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
   <style>
     @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        /* Conteneur pour afficher les graphiques des dépenses et des revenus en pie chart */
        .charts-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            justify-content: space-between;
        }

        canvas {
    max-width: 100%;
    height: auto;
}


        /* Conteneur pour les graphiques de comparaison et les courbes */
        .comparison-charts-container {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }

        /* S'assurer que les graphiques de comparaison sont correctement espacés */
        .comparison-container {
            width: 48%;
        }

        /* Conteneur pour les courbes en ligne */
        .line-charts-container {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }

        .line-chart-container {
            width: 48%;
        }
        body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f8f9fa;
    color: #333;
}
.main-content {
    margin-top: 60px; /* Ajuster en fonction de la hauteur de votre navbar */
}
.dashboard{
  margin-left:50px;
  padding-left: 260px; /* Ajuster en fonction de la largeur de votre sidebar */
  padding-top: 20px;


}

.navbar {
    position: fixed;
    margin-top: 0px;
    width: 100%;
    z-index: 999;
    background: #333;
    color: white;
    padding: 15px;
}
    </style>
<body>
<div class="dashboard">
   
    <div class="charts-container">
        <!-- Card for Dépenses par Catégorie -->
        <div class="card comparison-container" style="width: 40%; ">
            <div class="card-body text-center">
                <h3 class="card-title">Dépenses par Catégorie</h3>
                <canvas id="pieChartDepenses"></canvas>
            </div>
        </div>

        <!-- Card for Revenus par Catégorie -->
        <div class="card comparison-container" style="width: 40%;">
            <div class="card-body">
                <h3 class="card-title">Revenus par Catégorie</h3>
                <canvas id="pieChartRevenus"></canvas>
            </div>
        </div>

        <!-- Card for Progression des Objectifs -->
        <div class="card comparison-container" style="width: 40%;">
            <div class="card-body">
                <h3 class="card-title">Progression des Objectifs</h3>
                <canvas id="donutChartObjectifs"></canvas>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les graphiques en ligne (évolution des revenus et dépenses) -->
    <div class="line-charts-container">
        <div class="card line-chart-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Évolution des Dépenses sur les Mois</h3>
                <canvas id="lineChartDepenses"></canvas>
            </div>
        </div>

        <div class="card line-chart-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Évolution des Revenus sur les Mois</h3>
                <canvas id="lineChartRevenus"></canvas>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les graphiques comparatifs (revenus vs dépenses) -->
    <div class="comparison-charts-container">
        <div class="card comparison-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Comparaison des Revenus et Dépenses Mensuels</h3>
                <canvas id="barChartComparison"></canvas>
            </div>
        </div>

        <div class="card comparison-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Comparaison des Revenus et Dépenses de l'Année Dernière</h3>
                <canvas id="barChartComparisonAnneePrecedente"></canvas>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les graphiques des années et épargnes -->
    <div class="line-charts-container">
        <div class="card line-chart-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Évolution des Dépenses de l'Année Dernière</h3>
                <canvas id="lineChartDepensesAnneePrecedente"></canvas>
            </div>
        </div>

        <div class="card line-chart-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Évolution des Revenus de l'Année Dernière</h3>
                <canvas id="lineChartRevenusAnneePrecedente"></canvas>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les graphiques d'épargne -->
    <div class="line-charts-container">
        <div class="card line-chart-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Évolution des Épargnes Mensuelles</h3>
                <canvas id="lineChartEpargnesMensuelles"></canvas>
            </div>
        </div>

        <div class="card line-chart-container" style="width: 50%;">
            <div class="card-body">
                <h3 class="card-title">Évolution des Épargnes Annuelles</h3>
                <canvas id="lineChartEpargnesAnnuelles"></canvas>
            </div>
        </div>
    </div>
</div>

    <script>
      // Données récupérées depuis PHP pour les dépenses
      const pieDataDepenses = {
        labels: <?php echo json_encode($categories_depenses); ?>,  // Catégories
        datasets: [{
          label: 'Dépenses par Catégorie',
          data: <?php echo json_encode($montants_depenses); ?>,  // Montants des dépenses
          backgroundColor: ['#FF8383', '#FFF574', '#A1D6CB', '#A19AD3', '#CDC1FF','#474E93'], // Couleurs personnalisées
          hoverOffset: 4
        }]
      };

      const ctxPieDepenses = document.getElementById('pieChartDepenses').getContext('2d');
      new Chart(ctxPieDepenses, {
        type: 'pie',  // Type de graphique
        data: pieDataDepenses,
      });

      // Données récupérées depuis PHP pour les revenus
      const pieDataRevenus = {
        labels: <?php echo json_encode($categories_revenus); ?>,  // Catégories
        datasets: [{
          label: 'Revenus par Catégorie',
          data: <?php echo json_encode($montants_revenus); ?>,  // Montants des revenus
          backgroundColor: ['#640D5F', '#D91656', '#EB5B00', '#FFB200', '#FFF6E9'], // Couleurs personnalisées
          hoverOffset: 4
        }]
      };

      const ctxPieRevenus = document.getElementById('pieChartRevenus').getContext('2d');
      new Chart(ctxPieRevenus, {
        type: 'pie',  // Type de graphique
        data: pieDataRevenus,
      });

      // Données récupérées pour l'évolution des dépenses
      const lineDataDepenses = {
        labels: <?php echo json_encode($mois_depenses); ?>,  // Mois
        datasets: [{
          label: 'Évolution des Dépenses',
          data: <?php echo json_encode($montants_evolution_depenses); ?>,  // Montants des dépenses
          borderColor: '#FF5733',  // Couleur de la courbe
          backgroundColor: '#F29F58',  // Couleur de l'arrière-plan de la courbe
          fill: true,  // Remplir sous la courbe
          tension: 0.4  // Lissage de la courbe
        }]
      };

      const ctxLineDepenses = document.getElementById('lineChartDepenses').getContext('2d');
      new Chart(ctxLineDepenses, {
        type: 'line',  // Type de graphique
        data: lineDataDepenses,
      });
      const lineDataRevenus = {
        labels: <?php echo json_encode($mois_revenus); ?>,  // Mois
        datasets: [{
            label: 'Évolution des Revenus',
            data: <?php echo json_encode($montants_evolution_revenus); ?>,  // Montants des revenus
            borderColor: '#A888B5',  // Couleur de la courbe
            backgroundColor: '#A888B5',  // Couleur de l'arrière-plan de la courbe
            fill: true,  // Remplir sous la courbe
            tension: 0.4  // Lissage de la courbe
        }]
    };

    const ctxLineRevenus = document.getElementById('lineChartRevenus').getContext('2d');
    new Chart(ctxLineRevenus, {
        type: 'line',  // Type de graphique
        data: lineDataRevenus,
    });
    const moisLabels = <?php echo json_encode($mois_revenus); ?>;  // Mois
        const revenusData = <?php echo json_encode($montants_revenus); ?>;  // Montants des revenus
        const depensesData = <?php echo json_encode($montants_depenses); ?>;  // Montants des dépenses

        const ctx = document.getElementById('barChartComparison').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: moisLabels,  // Mois
                datasets: [
                    {
                        label: 'Revenus',
                        data: revenusData,
                        backgroundColor: '#E2BFD9',  // Couleur des barres de dépenses
                        borderColor: '#E2BFD9',
                        
                        borderWidth: 1
                    },
                    {
                        label: 'Dépenses',
                        data: depensesData,
                        backgroundColor: '#FFF7D1',  // Couleur des barres de revenus
                        borderColor: '#FFF7D1', 
                        
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                      stacked: true   // Superposition des barres
                    },
                    y: {
                      stacked: true  // Commencer l'axe Y à zéro
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });
        // Données pour l'évolution des dépenses de l'année dernière
const lineDataDepensesAnneePrecedente = {
  labels: <?php echo json_encode($mois_depenses_annee_precedente); ?>,  // Mois
  datasets: [{
    label: 'Évolution des Dépenses',
    data: <?php echo json_encode($montants_depenses_annee_precedente); ?>,  // Montants des dépenses
    borderColor: '#FF5733',
    backgroundColor: 'rgba(255, 87, 51, 0.2)',
    fill: true,
    tension: 0.4
  }]
};

const ctxLineDepensesAnneePrecedente = document.getElementById('lineChartDepensesAnneePrecedente').getContext('2d');
new Chart(ctxLineDepensesAnneePrecedente, {
  type: 'line',
  data: lineDataDepensesAnneePrecedente,
});

// Données pour l'évolution des revenus de l'année dernière
const lineDataRevenusAnneePrecedente = {
  labels: <?php echo json_encode($mois_revenus_annee_precedente); ?>,  // Mois
  datasets: [{
    label: 'Évolution des Revenus',
    data: <?php echo json_encode($montants_revenus_annee_precedente); ?>,  // Montants des revenus
    borderColor: '#4CAF50',
    backgroundColor: 'rgba(76, 175, 80, 0.2)',
    fill: true,
    tension: 0.4
  }]
};

const ctxLineRevenusAnneePrecedente = document.getElementById('lineChartRevenusAnneePrecedente').getContext('2d');
new Chart(ctxLineRevenusAnneePrecedente, {
  type: 'line',
  data: lineDataRevenusAnneePrecedente,
});

// Comparaison des revenus et dépenses de l'année dernière
const moisLabelsAnneePrecedente = <?php echo json_encode($mois_revenus_annee_precedente); ?>;
const revenusDataAnneePrecedente = <?php echo json_encode($montants_revenus_annee_precedente); ?>;
const depensesDataAnneePrecedente = <?php echo json_encode($montants_depenses_annee_precedente); ?>;

const ctxComparisonAnneePrecedente = document.getElementById('barChartComparisonAnneePrecedente').getContext('2d');
new Chart(ctxComparisonAnneePrecedente, {
  type: 'bar',
  data: {
    labels: moisLabelsAnneePrecedente,
    datasets: [
      {
        label: 'Revenus',
        data: revenusDataAnneePrecedente,
        backgroundColor: '#E3A5C7',
        borderColor: '#E3A5C7',
        borderWidth: 1
      },
      {
        label: 'Dépenses',
        data: depensesDataAnneePrecedente,
        backgroundColor: 'rgba(76, 175, 80, 0.7)',
        borderColor: 'rgba(76, 175, 80, 1)',
        borderWidth: 1
      }
    ]
  },
  options: {
    responsive: true,
    scales: {
      x: {
        stacked: true
      },
      y: {
        stacked: true
      }
    },
    plugins: {
      legend: {
        position: 'top',
      },
      tooltip: {
        mode: 'index',
        intersect: false,
      }
    }
  }
});
const donutDataObjectifs = {
    labels: <?php echo json_encode($noms_objectifs); ?>, // Noms des objectifs
    datasets: [{
        label: 'Progression des Objectifs',
        data: <?php echo json_encode($progression_objectifs); ?>, // Progression en pourcentage
        backgroundColor: ['#FFD700', '#FF6347', '#4682B4', '#32CD32', '#FF69B4'], // Couleurs
        hoverOffset: 4
    }]
};

const ctxDonutObjectifs = document.getElementById('donutChartObjectifs').getContext('2d');
new Chart(ctxDonutObjectifs, {
    type: 'doughnut', // Type de graphique
    data: donutDataObjectifs,
});
const lineDataEpargnesMensuelles = {
    labels: <?php echo json_encode($mois_epargnes); ?>,  // Mois
    datasets: [{
      label: 'Évolution des Épargnes Mensuelles',
      data: <?php echo json_encode($montants_epargnes_mensuelles); ?>,  // Montants des épargnes
      borderColor: '#28A745',  // Couleur de la courbe
      backgroundColor: '#B2E8A3',  // Couleur de l'arrière-plan de la courbe
      fill: true,  // Remplir sous la courbe
      tension: 0.4  // Lissage de la courbe
    }]
  };

  const ctxLineEpargnesMensuelles = document.getElementById('lineChartEpargnesMensuelles').getContext('2d');
  new Chart(ctxLineEpargnesMensuelles, {
    type: 'line',  // Type de graphique
    data: lineDataEpargnesMensuelles,
  });

  // Données récupérées pour les épargnes annuelles
  const lineDataEpargnesAnnuales = {
    labels: <?php echo json_encode($annees_epargnes); ?>,  // Années
    datasets: [{
      label: 'Évolution des Épargnes Annuelles',
      data: <?php echo json_encode($montants_epargnes_annuelles); ?>,  // Montants des épargnes
      borderColor: '#17A2B8',  // Couleur de la courbe
      backgroundColor: '#A0D1E8',  // Couleur de l'arrière-plan de la courbe
      fill: true,  // Remplir sous la courbe
      tension: 0.4  // Lissage de la courbe
    }]
  };

  const ctxLineEpargnesAnnuales = document.getElementById('lineChartEpargnesAnnuelles').getContext('2d');
  new Chart(ctxLineEpargnesAnnuales, {
    type: 'line',  // Type de graphique
    data: lineDataEpargnesAnnuales,
  });
    </script>
</body>
</html>
