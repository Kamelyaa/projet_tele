<?php
// Vérifier si la clé secrète est fournie dans la requête POST
if(isset($_POST['secret_key'])) {
    if($_POST['secret_key'] === "azertyuiop") {
        // Si la clé secrète est correcte, rediriger vers la page adm1.php
        header("Location: admin.php");
        exit;
    } else {
        // Clé secrète invalide, afficher un message d'erreur
        echo "<p style='color: red;'>Clé secrète incorrecte. Veuillez réessayer.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de clé secrète</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
        }
        input[type="password"] {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        p.error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Veuillez saisir la clé secrète</h2>
        <form action="adm1.php" method="post">
            <label for="secret_key">Clé secrète:</label>
            <input type="password" id="secret_key" name="secret_key" required>
            <input type="submit" value="Valider">
        </form>
        <?php
        if(isset($_POST['secret_key']) && $_POST['secret_key'] !== "azertyuiop") {
            echo "<p class='error'>Clé secrète incorrecte. Veuillez réessayer.</p>";
        }
        ?>
    </div>
</body>
</html>
