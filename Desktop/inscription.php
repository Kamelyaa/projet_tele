<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Assurez-vous d'inclure FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"],
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Masquer par défaut les champs du médecin */
        .medecin-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="nom">Nom :</label><br>
            <input type="text" id="nom" name="nom" required><br>
            <label for="prenom">Prénom :</label><br>
            <input type="text" id="prenom" name="prenom" required><br>
            <label for="password">Mot de passe :</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="adresse">Adresse :</label><br>
            <input type="text" id="adresse" name="adresse" required><br>
            <label for="age">Âge :</label><br>
            <input type="number" id="age" name="age" required><br>
            <label for="sexe">Sexe :</label><br>
            <select id="sexe" name="sexe" required>
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
                <option value="autre">Autre</option>
            </select><br>
            <label for="email">Email :</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="numero_telephone">Numéro de téléphone :</label><br>
            <input type="text" id="numero_telephone" name="numero_telephone"><br>
            <!-- Ajoutez le code du CAPTCHA ici -->
            <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

            <!-- Bouton basculant pour les champs du médecin -->
            <button type="button" onclick="toggleMedecinFields()">Je suis un médecin</button>

            <!-- Champs spécifiques au médecin -->
            <div class="medecin-fields">
                <label for="specialite">Spécialité :</label><br>
                <input type="text" id="specialite" name="specialite"><br>
                <label for="experience">Expérience :</label><br>
                <input type="number" id="experience" name="experience"><br>
                <label for="adresse_cabinet">Adresse du cabinet :</label><br>
                <input type="text" id="adresse_cabinet" name="adresse_cabinet"><br>
            </div>

            <br>
            <input type="submit" value="S'inscrire">
        </form>
        <?php
        // Votre script PHP pour enregistrer les données dans la base de données
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "teleconsultation_db";
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("Erreur de connexion à la base de données : " . $conn->connect_error);
            }

            // Validation des entrées
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $adresse = htmlspecialchars($_POST['adresse']);
            $age = htmlspecialchars($_POST['age']);
            $sexe = htmlspecialchars($_POST['sexe']);
            $email = htmlspecialchars($_POST['email']);
            $numero_telephone = isset($_POST['numero_telephone']) ? htmlspecialchars($_POST['numero_telephone']) : '';
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

            // Si les champs spécifiques au médecin sont vides, enregistrer dans la table patients
            if (empty($_POST['specialite']) && empty($_POST['experience']) && empty($_POST['adresse_cabinet'])) {
                // Requête préparée pour insérer les données du patient
                $stmt = $conn->prepare("INSERT INTO patients (nom, prenom, adresse, age, sexe, email, numero_telephone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssissss", $nom, $prenom, $adresse, $age, $sexe, $email, $numero_telephone, $password);
                $redirect_url = "accueil_pat.php"; // URL de redirection pour les patients
            } else {
                $specialite = htmlspecialchars($_POST['specialite']);
                $experience = htmlspecialchars($_POST['experience']);
                $adresse_cabinet = htmlspecialchars($_POST['adresse_cabinet']);
                // Requête préparée pour insérer les données du médecin
                $stmt = $conn->prepare("INSERT INTO medecins (nom, prenom, adresse, age, sexe, email, numero_telephone, password, specialite, experience, adresse_cabinet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssisssssss", $nom, $prenom, $adresse, $age, $sexe, $email, $numero_telephone, $password, $specialite, $experience, $adresse_cabinet);
                $redirect_url = "accueil_med.php"; // URL de redirection pour les médecins
            }

            // Exécution de la requête
            if ($stmt->execute()) {
                // Redirection vers la page d'accueil appropriée
                header("Location: $redirect_url");
                exit(); // Assurez-vous de terminer le script après la redirection
            } else {
                if ($conn->errno == 1062) {
                    echo "<p style='color:red;'>Cette adresse e-mail est déjà utilisée.</p>";
                } else {
                    echo "Erreur: " . $stmt->error;
                }
            }

            // Fermeture de la connexion et de la requête
            $stmt->close();
            $conn->close();
        }
        ?>

        <!-- JavaScript pour basculer la visibilité des champs spécifiques au médecin -->
        <script>
            function toggleMedecinFields() {
                var medecinFields = document.querySelector('.medecin-fields');
                // Inversion de la visibilité des champs spécifiques au médecin
                if (medecinFields.style.display === 'none') {
                    medecinFields.style.display = 'block';
                } else {
                    medecinFields.style.display = 'none';
                }
            }
        </script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </div>
</body>
</html>
