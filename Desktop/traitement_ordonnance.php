<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_patient']) && isset($_POST['details'])) {
        $id_patient = $_POST['id_patient'];
        $details = $_POST['details'];

        $servername = "localhost";
        $username = "root";
        $password_db = "root";
        $dbname = "teleconsultation_db";

        $conn = new mysqli($servername, $username, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connexion échouée : " . $conn->connect_error);
        }

        $pdfPath = "";

        if(isset($_FILES['pdf'])) {
            $targetDir = "uploads/";
            $pdfPath = $targetDir . basename($_FILES["pdf"]["name"]);
            move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdfPath);
        }

        $sql = "INSERT INTO ordonnances (id_patient, details, pdf_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $id_patient, $details, $pdfPath);

        if ($stmt->execute()) {
            echo "Ordonnance ajoutée avec succès.";
        } else {
            echo "Erreur lors de l'ajout de l'ordonnance : " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Tous les champs requis ne sont pas présents dans la requête POST.";
    }
} else {
    echo "Le formulaire n'a pas été soumis.";
}
?>
