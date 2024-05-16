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
            background-color: #f0f0f0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .appointment-list {
            list-style-type: none;
            padding-left: 0;
        }

        .appointment-list li {
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .profile img {
            width: 60px;
            height: 60px;
            right: 20px;
            border-radius: 50%;
            cursor: pointer;
        }
        .chat-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ccc;
            overflow-y: auto;
            max-height: 200px;
        }

        .message {
            margin-bottom: 10px;
        }

        .message span {
            font-weight: bold;
            color: #007bff;
        }

        .message p {
            margin: 5px 0;
        }
    </style>
</head>
<header>
    <div class="logo">
        <img src="images/docto.png" alt="Logo Médecin" onclick="openCentral()">
        <h1>Bienvenue </h1>
    </div>
    <div class="profile">
        <img src="images/profil.webp" alt="Photo de profil" onclick="openProfile(<?php echo $_SESSION['user_id']; ?>)">
    </div>
</header>
<body>
    <div class="container">
        <h2>Historique des rendez-vous</h2>
        
        <!-- Formulaire pour saisir le prénom -->
        <form method="POST">
            <label for="prenom">Prénom du patient :</label>
            <input type="text" id="prenom" name="prenom">
            <button type="submit">Vérifier les rendez-vous</button>
        </form>
        
        <!-- Liste des rendez-vous -->
        <div class="appointment-list">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
                    $prenom = $_POST['prenom'];
                    // Appel de la fonction pour afficher les rendez-vous
                    getAppointmentHistory($prenom);
                } else {
                    echo "<p>Veuillez saisir un prénom.</p>";
                }
            }

            // Fonction pour récupérer l'historique des rendez-vous du patient
            function getAppointmentHistory($prenom) {
                $servername = "localhost";
                $username = "root";
                $password_db = "root";
                $dbname = "teleconsultation_db";
                
                // Connexion à la base de données
                $conn = new mysqli($servername, $username, $password_db, $dbname);

                // Vérifier la connexion
                if ($conn->connect_error) {
                    die("Échec de la connexion : " . $conn->connect_error);
                }

                // Requête SQL pour vérifier si le prénom existe dans la table rdv
                $sql = "SELECT * FROM rdv WHERE prenom_patient = '$prenom'";

                // Exécution de la requête
                $result = $conn->query($sql);

                // Vérifier s'il y a des résultats
                if ($result->num_rows > 0) {
                    // Afficher les rendez-vous
                    echo "<h3>Rendez-vous pour $prenom :</h3>";
                    echo "<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>Rendez-vous le " . $row["date_rdv"] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Aucun rendez-vous trouvé pour $prenom.</p>";
                }

                // Fermer la connexion à la base de données
                $conn->close();
            }
            ?>
        </div>

        <!-- Container pour afficher les messages -->
        <div class="chat-container">
            <h2>Messages</h2>
            <?php
            // URL de l'API pour récupérer les messages
            $url = 'http://localhost:3000/messages';

            // Récupération des messages depuis l'API
            $response = file_get_contents($url);
            $messages = json_decode($response, true);

            // Affichage des messages depuis l'API dans la section d'historique
            foreach ($messages as $message) {
                echo "<div class='message'><span>{$message['user']} :</span><p>{$message['text']}</p></div>";
            }
            ?>
        </div>
    </div>

    <script>
        function openCentral() {
            
            window.location.href = "accueil_pat.php";
        }
    </script>
</body>
</html>
