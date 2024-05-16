<?php
function generateCalendar($year, $month) {
    $firstDayOfMonth = strtotime("$year-$month-01");
    $lastDayOfMonth = strtotime(date("Y-m-t", $firstDayOfMonth));

    $calendarHTML = '<table id="calendarTable">';
    $calendarHTML .= '<thead><tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr></thead>';
    $calendarHTML .= '<tbody>';

    $startDayOfWeek = date('N', $firstDayOfMonth);
    $calendarHTML .= '<tr>';
    for ($i = 1; $i < $startDayOfWeek; $i++) {
        $calendarHTML .= '<td></td>';
    }

    for ($day = 1; $day <= date('t', $firstDayOfMonth); $day++) {
        if ($startDayOfWeek > 7) {
            $startDayOfWeek = 1;
            $calendarHTML .= '</tr><tr>';
        }
        // Ajoutez un bouton cliquable pour chaque jour
        if (($month == 5 && $day == 17) || ($month == 5 && $day == 22)) {
            // Si la date est le 17 mai ou le 22 mai, affichez le rendez-vous spécifique
            $calendarHTML .= "<td><button onclick='handleDayClick($year, $month, $day)'>Rdv Dr Saber</button></td>";
        } else {
            // Sinon, affichez le jour normal
            $calendarHTML .= "<td><button onclick='handleDayClick($year, $month, $day)'>$day</button></td>";
        }
        $startDayOfWeek++;
    }

    while ($startDayOfWeek <= 7) {
        $calendarHTML .= '<td></td>';
        $startDayOfWeek++;
    }

    $calendarHTML .= '</tr></tbody></table>';
    return $calendarHTML;
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

$prevMonthYear = $month - 1 < 1 ? $year - 1 : $year;
$prevMonth = $month - 1 < 1 ? 12 : $month - 1;

$nextMonthYear = $month + 1 > 12 ? $year + 1 : $year;
$nextMonth = $month + 1 > 12 ? 1 : $month + 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #calendarContainer {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            text-align: center;
        }

        #calendarTable {
            width: 100%;
            border-collapse: collapse;
        }

        #calendarTable th,
        #calendarTable td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        #calendarTable th {
            background-color: #f0f0f0;
        }

        #calendarTable td {
            background-color: #fff;
        }

        #calendarNavigation {
            text-align: center;
            margin-bottom: 20px;
        }

        #calendarNavigation button {
            padding: 5px 10px;
            font-size: 14px;
            margin: 0 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="calendarNavigation">
        <button onclick="updateCalendar(<?php echo $prevMonthYear; ?>, <?php echo $prevMonth; ?>)">&lt; Mois précédent</button>
        <h2 id="monthName"><?php echo date('F', mktime(0, 0, 0, $month, 1, $year)) . " $year"; ?></h2>
        <button onclick="updateCalendar(<?php echo $nextMonthYear; ?>, <?php echo $nextMonth; ?>)">Mois suivant &gt;</button>
    </div>

    <div id="calendarContainer">
        <?php echo generateCalendar($year, $month); ?>
    </div>
    
    <script>
        function updateCalendar(year, month) {
            fetch(`agendapat.php?year=${year}&month=${month}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("calendarContainer").innerHTML = data;
                    document.getElementById("monthName").innerText = new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
                });
        }

        function handleDayClick(year, month, day) {
            if ((month == 5 && day == 17) || (month == 5 && day == 22)) {
                // Si la date est le 17 mai ou le 22 mai, affichez le rendez-vous spécifique
                alert("Rdv Dr Saber \nLien : http://localhost:3000");
            } else {
                // Sinon, affichez le message par défaut
                alert(`Vous avez cliqué sur le ${day}/${month}/${year}`);
            }
        }
    </script>
</body>
</html>
