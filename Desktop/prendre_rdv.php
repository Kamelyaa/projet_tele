<?php
session_start();

$servername = "localhost";
$username = "root";
$password_db = "root"; // Mettez le mot de passe de votre base de données ici
$dbname = "teleconsultation_db";
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Si le formulaire pour vérifier le patient est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_patient'])) {
    // Vérifier que les champs ne sont pas vides
    if (!empty($_POST['prenom']) && !empty($_POST['nom'])) {
        // Récupérer les données du formulaire
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];

        // Vérifier si le patient existe dans la base de données
        $stmt = $conn->prepare("SELECT * FROM patients WHERE prenom = ? AND nom = ?");
        $stmt->bind_param("ss", $prenom, $nom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Afficher le formulaire pour choisir le médecin et la date de rendez-vous
            $medecins_options = '';

            // Exécuter la requête SQL pour récupérer les médecins et leur spécialité
            $sql = "SELECT * FROM medecins";
            $result = $conn->query($sql);

            // Traiter les résultats de la requête
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $medecins_options .= "<option value='" . $row["id"] . "'>" . $row["nom"] . " - " . $row["specialite"] . "</option>";
                }
            }

            // Afficher le formulaire pour prendre rendez-vous
            echo "
            <h2>Choisissez un médecin :</h2>
            <form method='post' action=''>
                <input type='hidden' name='prenom' value='$prenom'>
                <input type='hidden' name='nom' value='$nom'>
                <select id='medecinSelect' name='medecinSelect'>
                    <option value=''>Sélectionnez un médecin</option>
                    $medecins_options
                </select>
                <section id='disponibilitesSection'>
                    <h2>Disponibilités :</h2>
                    <input type='datetime-local' id='dateRdv' name='dateRdv'>
                </section>
                <input type='submit' value='Prendre rendez-vous' name='take_appointment'>
            </form>
            <form method='get' action='accueil_pat.php'>
                <input type='submit' value='Retour'>
            </form>";
        } else {
            echo "<script>alert('Patient non trouvé. Veuillez vérifier vos informations.');</script>";
        }

        // Fermer la requête
        $stmt->close();
    } else {
        echo "<script>alert('Tous les champs doivent être remplis.');</script>";
    }
}

// Si le formulaire pour prendre un rendez-vous est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['take_appointment'])) {
    // Vérifier que les champs ne sont pas vides
    if (!empty($_POST['medecinSelect']) && !empty($_POST['dateRdv']) && !empty($_POST['prenom'])) {
        // Récupérer les données du formulaire
        $id_medecin = $_POST['medecinSelect'];
        $date_rdv = $_POST['dateRdv'];
        $prenom_patient = $_POST['prenom'];

        // Insérer le rendez-vous dans la table rdv
        $stmt = $conn->prepare("INSERT INTO rdv (id_medecin, date_rdv, prenom_patient) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_medecin, $date_rdv, $prenom_patient);
        if ($stmt->execute()) {
            echo "<script>alert('Rendez-vous pris avec succès');</script>";
        } else {
            echo "<script>alert('Erreur lors de la prise de rendez-vous');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Tous les champs doivent être remplis.');</script>";
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un Rendez-vous</title>
    <style>
        /* Styles CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
        }

        main {
            padding: 20px;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        p {
            margin-bottom: 10px;
            text-align: center;
        }

        select, input[type="submit"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
            display: block;
            margin: auto;
        }

        #medecinSelect {
            width: 100%;
            max-width: 300px;
        }

        #disponibilitesSection {
            text-align: center;
        }

        #disponibilitesSelect {
            margin-top: 10px;
            width: 100%;
            max-width: 300px;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        footer {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Prendre un Rendez-vous</h1>
    </header>
    <main>
        <section id="patient-content">
            <h2>Entrez vos informations :</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="submit" value="Vérifier" name="verify_patient">
            </form>
        </section>
    </main>
    <footer>
        <!-- Pied de page -->
    </footer>
</body>
</html>
