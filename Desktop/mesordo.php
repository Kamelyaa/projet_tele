<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: aquamarine; /* Fond vert d'eau */
        }
        /* Ajoutez ici votre CSS personnalisé */
        .ordonnance {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .pdf-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .pdf-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password_db = "root";
    $dbname = "teleconsultation_db";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    session_start();
    if (isset($_SESSION['user_id'])) {
        $id_patient = $_SESSION['user_id'];

        $sql = "SELECT details, pdf_path FROM ordonnances WHERE id_patient = $id_patient";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='ordonnance'>" . $row["details"] . "</div>";
                echo "<a href=/" . $row["pdf_path"] . "' target='_blank'>Consulter le PDF</a>";
            }
        } else {
            echo "Aucune ordonnance trouvée pour ce patient.";
        }
    } else {
        echo "ID du patient non spécifié.";
    }

    $conn->close();
    ?>
</body>
</html>