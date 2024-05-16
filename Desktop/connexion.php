<?php
session_start(); // Démarrer la session

// Vérification après la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des entrées du formulaire
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password_db = "root"; // Mettez le mot de passe de votre base de données ici
    $dbname = "teleconsultation_db";
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    // Préparation de la requête SQL pour les patients
    $stmt_patients = $conn->prepare("SELECT id, prenom, password FROM patients WHERE email = ?");
    $stmt_patients->bind_param("s", $email);
    $stmt_patients->execute();
    $result_patients = $stmt_patients->get_result();

    // Préparation de la requête SQL pour les médecins
    $stmt_medecins = $conn->prepare("SELECT id, prenom, password FROM medecins WHERE email = ?");
    $stmt_medecins->bind_param("s", $email);
    $stmt_medecins->execute();
    $result_medecins = $stmt_medecins->get_result();

    // Vérification du résultat pour les patients
    if ($result_patients->num_rows > 0) {
        // Récupération de l'utilisateur
        $user = $result_patients->fetch_assoc();

        // Vérification du mot de passe pour les patients
        if (password_verify($password, $user['password'])) {
            // Redirection vers la page d'accueil des patients
            $_SESSION['user_id'] = $user['id']; // Stocker l'ID du patient dans la session
            $_SESSION['nom_patient'] = $user['prenom'];
            header("Location: accueil_pat.php");
            exit();
        } else {
            // Les informations d'identification sont incorrectes
            $_SESSION['login_error'] = true;
            header("Location: connexion.php");
            exit();
        }
    }

    // Vérification du résultat pour les médecins
    if ($result_medecins->num_rows > 0) {
        // Récupération de l'utilisateur
        $user = $result_medecins->fetch_assoc();

        // Vérification du mot de passe pour les médecins
        if (password_verify($password, $user['password'])) {
            // Redirection vers la page d'accueil des médecins
            $_SESSION['user_id'] = $user['id']; // Stocker l'ID du médecin dans la session
            $_SESSION['user_type'] = 'medecin';
            header("Location: accueil_med.php");
            exit();
        } else {
            // Les informations d'identification sont incorrectes
            $_SESSION['login_error'] = true;
            header("Location: connexion.php");
            exit();
        }
    }

    // Si aucun utilisateur correspondant n'est trouvé
    $_SESSION['login_error'] = true;
    header("Location: connexion.php");
    exit();

    // Fermeture de la connexion
    $stmt_patients->close();
    $stmt_medecins->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
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

input[type="email"],
input[type="password"],
input[type="submit"] {
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

/* Styles pour les messages d'erreur */
.error-message {
    color: red;
    text-align: center;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="email">Adresse email :</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Mot de passe :</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Se connecter">
        </form>
        <?php
        // Affichage de l'erreur de connexion si nécessaire
        if (isset($_SESSION['login_error']) && $_SESSION['login_error'] === true) {
            echo "<p style='color:red;'>Adresse email ou mot de passe incorrect.</p>";
            unset($_SESSION['login_error']); // Supprimer la variable de session après affichage de l'erreur
        }
        ?>
    </div>
</body>
</html>