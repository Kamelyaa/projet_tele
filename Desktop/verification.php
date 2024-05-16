<?php
session_start();

// Récupérer le code de vérification depuis le fichier texte
$verification_file = "secret_code.txt";
$generated_code = file_get_contents($verification_file);

// Vérification après la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération du code de vérification saisi par l'utilisateur
    $verification_code = htmlspecialchars($_POST['verification_code']);

    // Vérification du code de vérification
    if ($verification_code === $generated_code) {
        // Vérification de la session de type utilisateur
        if (isset($_SESSION['user_type'])) {
            // Redirection vers la page d'accueil appropriée
            if ($_SESSION['user_type'] === 'patient') {
                header("Location: accueil_pat.php");
                exit();
            } elseif ($_SESSION['user_type'] === 'medecin') {
                header("Location: accueil_med.php");
                exit();
            } else {
                // Gestion des cas d'erreur
            }
        } else {
            // Type d'utilisateur non défini, rediriger vers la page de connexion
            header("Location: connexion.php");
            exit();
        }
    } else {
        // Codes non correspondants, afficher un message d'erreur
        $_SESSION['verification_error'] = true;
        header("Location: verification.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification</title>
</head>
<body>
    <div class="container">
        <h2>Entrez le code de vérification</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="verification_code">Code de vérification :</label><br>
            <input type="text" id="verification_code" name="verification_code" required><br>
            <input type="submit" value="Valider">
        </form>
        <?php
        // Affichage de l'erreur de vérification si nécessaire
        if (isset($_SESSION['verification_error']) && $_SESSION['verification_error'] === true) {
            echo "<p style='color:red;'>Code de vérification incorrect.</p>";
            unset($_SESSION['verification_error']); // Supprimer la variable de session après affichage de l'erreur
        }
        ?>
    </div>
</body>
</html>
