<?php
// Démarrer la session
session_start();

// Fonction pour mettre à jour les informations du patient dans la base de données
function updatePatientInfo($conn, $patientId, $field, $value) {
    $sql = "UPDATE patients SET $field = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $value, $patientId); // "si" indique que le premier paramètre est une chaîne et le deuxième un entier
    if ($stmt->execute()) {
        return true; // Mise à jour réussie
    } else {
        return false; // Échec de la mise à jour
    }
}

// Vérifier si l'ID du patient est stocké dans la session
if (isset($_SESSION['user_id'])) {
    // Récupérer l'ID du patient depuis la session
    $patientId = $_SESSION['user_id'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "teleconsultation_db";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Si le formulaire de modification est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier quel champ doit être modifié
        if (isset($_POST['field']) && isset($_POST['value'])) {
            $field = $_POST['field'];
            $value = $_POST['value'];
            // Mettre à jour les informations du patient dans la base de données
            if (updatePatientInfo($conn, $patientId, $field, $value)) {
                echo "<div style='color: green; font-size: 18px; margin-bottom: 10px;'>Les modifications ont été enregistrées avec succès.</div>";
            } else {
                echo "<div style='color: red; font-size: 18px; margin-bottom: 10px;'>Erreur lors de l'enregistrement des modifications.</div>";
            }
        }
    }

    // Récupérer les informations actuelles du patient depuis la base de données
    $sql = "SELECT * FROM patients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Afficher les informations du patient
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom = $row["nom"];
        $prenom = $row["prenom"];
        $age = $row["age"];
        $adresse = $row["adresse"];
        $sexe = $row["sexe"];
        $email = $row["email"];

        echo "<div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9;'>";
        echo "<h2 style='color: #333; font-size: 24px; margin-bottom: 10px;'>Profil de $prenom $nom</h2>";
        echo "<form method='post'>";
        echo "<p style='color: #666; font-size: 18px; margin-bottom: 8px;'><strong>Date de naissance :</strong> <input type='text' name='value' value='$age'><button type='submit' name='field' value='age'>Enregistrer</button></p>";
        echo "<p style='color: #666; font-size: 18px; margin-bottom: 8px;'><strong>Adresse :</strong> <input type='text' name='value' value='$adresse'><button type='submit' name='field' value='adresse'>Enregistrer</button></p>";
        echo "<p style='color: #666; font-size: 18px; margin-bottom: 8px;'><strong>Sexe :</strong> <input type='text' name='value' value='$sexe'><button type='submit' name='field' value='sexe'>Enregistrer</button></p>";
        echo "<p style='color: #666; font-size: 18px; margin-bottom: 8px;'><strong>Email :</strong> <input type='text' name='value' value='$email'><button type='submit' name='field' value='email'>Enregistrer</button></p>";
        echo "</form>";

        // Ajout du bouton de déconnexion
        echo "<form method='post' action='index.html'>";
        echo "<button type='submit' name='logout'>Se déconnecter</button>";
        echo "</form>";

        echo "</div>";
    } else {
        echo "Aucun patient trouvé avec cet ID.";
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
} else {
    echo "ID du patient non spécifié.";
}
?>
