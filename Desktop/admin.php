<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion Admin</h2>
        <form action="admin.php" method="post">
            <label for="login">Login:</label><br>
            <input type="text" id="login" name="login" required><br>
            <label for="password">Mot de passe:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Se connecter">
        </form>
        <?php
        // Vérifier si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Vérifier les identifiants de connexion
            $login = $_POST['login'];
            $password = $_POST['password'];
        
            // Vérifier les identifiants admin
            if ($login === "kamelya93" && $password === "vitrygtr") {
                // Connexion réussie, afficher les tables demandées
        
                // Connexion à la base de données
                $mysqli = new mysqli("localhost", "root", "root", "teleconsultation_db");
                if ($mysqli->connect_error) {
                    die("Échec de la connexion à la base de données: " . $mysqli->connect_error);
                }
        
                // Affichage des données de la table "medecins"
                $sql_medecins = "SELECT * FROM medecins";
                $result_medecins = $mysqli->query($sql_medecins);
        
                if ($result_medecins->num_rows > 0) {
                    echo "<h2>Données de la table 'medecins'</h2>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Age</th><th>Sexe</th><th>Email</th></tr>";
                    while ($row_medecins = $result_medecins->fetch_assoc()) {
                        echo "<tr><td>" . $row_medecins["id"] . "</td><td>" . $row_medecins["nom"] . "</td><td>" . $row_medecins["prenom"] . "</td><td>" . $row_medecins["adresse"] . "</td><td>" . $row_medecins["age"] . "</td><td>" . $row_medecins["sexe"] . "</td><td>" . $row_medecins["email"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Aucune donnée trouvée dans la table 'medecins'.";
                }
                
        
                // Affichage des données de la table "patients"
                $sql_patients = "SELECT * FROM patients";
                $result_patients = $mysqli->query($sql_patients);
        
                if ($result_patients->num_rows > 0) {
                    echo "<h2>Données de la table 'patients'</h2>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Age</th><th>Sexe</th><th>Email</th><th>Numéro de téléphone</th></tr>";
                    while ($row_patients = $result_patients->fetch_assoc()) {
                        echo "<tr><td>" . $row_patients["id"] . "</td><td>" . $row_patients["nom"] . "</td><td>" . $row_patients["prenom"] . "</td><td>" . $row_patients["adresse"] . "</td><td>" . $row_patients["age"] . "</td><td>" . $row_patients["sexe"] . "</td><td>" . $row_patients["email"] . "</td><td>" . $row_patients["numero_telephone"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Aucune donnée trouvée dans la table 'patients'.";
                }
        
                $mysqli->close();
            } else {
                // Identifiants incorrects
                echo "Identifiants admin incorrects.";
            }
        }
        ?>
        <form action="inscription.php" method="post">
                <input type="submit" value="Inscrire une personne">
        </form>
    </div>
</body>
</html>
