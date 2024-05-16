<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Médecin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
        }

        main {
            padding: 20px;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat img {
            width: 60px;
            height: auto;
            position: fixed;
            bottom: 20px;
            right: 20px;
            cursor: pointer;
        }

        #main-content {
            margin-top: 20px;
        }

        #calendarContainer {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            text-align: center;
        }

        #calendarTable {
            width: 100%;
            border-collapse: collapse;
        }

        #calendarTable th,
        #calendarTable td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        #calendarTable th {
            background-color: #f0f0f0;
        }

        #calendarTable td {
            background-color: #fff;
        }

        #calendarNavigation {
            text-align: center;
            margin-bottom: 20px;
        }

        #calendarNavigation button {
            padding: 5px 10px;
            font-size: 14px;
            margin: 0 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <!-- Logo et titre -->
        <div class="logo">
            <img src="images/docto.png" alt="Logo Médecin" onclick="openCentral()">
            <h1>Bienvenue, Dr. [Nom du médecin]</h1>
        </div>
        <!-- Photo de profil cliquable -->
        <div class="profile">
            <img src="images/profil.webp" alt="Photo de profil" onclick="openProfile()">
        </div>
    </header>
    <main>
        <!-- Boutons -->
        <div class="buttons">
            <button onclick="openAgenda()">Mon Agenda</button>
            <button onclick="openFichePatients()">Fiche Patients</button>
            <button onclick="openHistorique()">Historique</button>
            <button onclick="openOrdonnance()">Créer une ordonnance</button>
        </div>
        <!-- Photo de tchat -->
        <div class="chat">
            <img src="images/tchat.webp" alt="Photo de tchat" onclick="openChat()">
        </div>

        <!-- Contenu principal -->
        <div id="main-content"></div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        function openProfile($user) {
            // Redirection vers le profil du patient avec son ID
            window.location.href = "profilemed.php?id=" + $user;
        }

        function openAgenda() {
            // Charger agendamed.php avec AJAX
            $.ajax({
                url: "agendamed.php",
                type: "GET",
                success: function(response) {
                    $("#main-content").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement de l'agenda :", error);
                }
            });
            console.log("Ouvrir l'agenda du médecin");
        }

        function openFichePatients() {
            // Code pour ouvrir la fiche des patients
            window.location.href = "fichepatient.php";
        }

        function openHistorique() {
        
            window.location.href = "fichepatient.php";
        }

        function openChat() {
            var chatAppUrl = 'http://localhost:4000';
            window.open(chatAppUrl, '_blank');
        }
        
        function openOrdonnance() {
            window.location.href = "ajouter_ordonnance.php";
        }
    </script>
</body>
</html>
