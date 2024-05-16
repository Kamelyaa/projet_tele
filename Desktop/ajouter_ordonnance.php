<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une ordonnance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"],
        input[type="button"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Ajouter une ordonnance</h1>
    <form action="traitement_ordonnance.php" method="post" enctype="multipart/form-data">
        <label for="id_patient">ID du patient :</label>
        <!-- Inclure une liste déroulante pour sélectionner l'ID du patient -->
        <select name="id_patient" id="id_patient" required>
            <?php
            // Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password_db = "root"; // Mot de passe de la base de données
            $dbname = "teleconsultation_db";
            $conn = new mysqli($servername, $username, $password_db, $dbname);

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("Connexion échouée : " . $conn->connect_error);
            }

            // Récupérer les ID des patients depuis la table patients
            $sql = "SELECT id, nom, prenom FROM patients";
            $result = $conn->query($sql);

            // Afficher chaque ID de patient dans une option de la liste déroulante
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['prenom'] . " " . $row['nom'] . "</option>";
                }
            } else {
                echo "<option disabled>Aucun patient trouvé</option>";
            }

            // Fermer la connexion à la base de données
            $conn->close();
            ?>
        </select>
        <label for="details">Détails de l'ordonnance :</label><br>
        <textarea id="details" name="details" rows="4" cols="50" required></textarea><br><br>
        <label for="pdf">Document PDF :</label><br>
        <input type="file" id="pdf" name="pdf" accept=".pdf" required><br><br>
        <input type="submit" value="Ajouter l'ordonnance">
    </form>
    <!-- Bouton pour voir les ordonnances -->
    <input type="button" value="Voir les ordonnances" onclick="voirOrdonnances()">
    <!-- Conteneur pour afficher les ordonnances -->
    <div id="ordonnancesContainer"></div>

    <script>
        // Fonction pour récupérer et afficher les ordonnances
        function voirOrdonnances() {
            // Requête AJAX pour récupérer les ordonnances depuis le fichier PHP
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("ordonnancesContainer").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "mesordo.php", true);
            xhttp.send();
        }
    </script>
</body>
</html>
