<?php
// Fonction pour générer le calendrier pour un mois donné
function generateCalendar($year, $month) {
    // Déterminer le premier jour et le dernier jour du mois
    $firstDayOfMonth = strtotime("$year-$month-01");
    $lastDayOfMonth = strtotime(date("Y-m-t", $firstDayOfMonth));

    // Créer une variable pour stocker le HTML du calendrier
    $calendarHTML = '<tr>';

    // Ajouter des cellules vides pour les jours précédant le premier jour du mois
    $startDayOfWeek = date('N', $firstDayOfMonth); // 1 (lundi) à 7 (dimanche)
    for ($i = 1; $i < $startDayOfWeek; $i++) {
        $calendarHTML .= '<td></td>';
    }

    // Ajouter les jours du mois
    for ($day = 1; $day <= date('t', $firstDayOfMonth); $day++) {
        // Si c'est le premier jour de la semaine, commencer une nouvelle ligne
        if ($startDayOfWeek > 7) {
            $startDayOfWeek = 1;
            $calendarHTML .= '</tr><tr>';
        }

        // Ajouter la cellule du jour
        $calendarHTML .= "<td>$day</td>";

        // Passer au jour suivant
        $startDayOfWeek++;
    }

    // Ajouter des cellules vides pour compléter la dernière semaine du mois
    while ($startDayOfWeek <= 7) {
        $calendarHTML .= '<td></td>';
        $startDayOfWeek++;
    }

    // Fermer la ligne
    $calendarHTML .= '</tr>';

    return $calendarHTML;
}

// Récupérer les paramètres GET
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// Générer le calendrier pour le mois spécifié
$calendar = generateCalendar($year, $month);

// Afficher le calendrier
echo $calendar;
?>
