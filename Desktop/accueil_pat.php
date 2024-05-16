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
            background-color: aquamarine; /* Fond vert d'eau */
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
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/docto.png" alt="Logo Médecin" onclick="openCentral()">
            <h1>Bienvenue </h1>
        </div>
        <div class="profile">
            <!-- Ajout d'un ID de patient pour le passage automatique -->
            <img src="images/profil.webp" alt="Photo de profil" onclick="openProfile(<?php echo $_SESSION['user_id']; ?>)">
        </div>
    </header>
    <main id="main-content">
        <div class="buttons">
            <button onclick="openAgenda()">Mon Agenda</button>
            <button onclick="openHistorique()">Historique</button>
            <button onclick="openOrdonnance()">Consulter mes ordonnances</button>
            <button onclick="openRdv()">Consulter mes Rendez-vous</button>
        </div>
        <div id="agenda-content">
            <!-- Contenu de l'agenda chargé ici -->
        </div>
        <div class="chat">
            <img src="images/tchat.webp" alt="Photo de tchat" onclick="openChat()">
        </div>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function openProfile($user) {
            // Redirection vers le profil du patient avec son ID
            window.location.href = "profile.php?id=" + $user;
        }

        function openCentral() {
            // Redirection vers le profil du patient avec son ID
            window.location.href = "accueil_pat.php";
        }

        function openRdv() {
            $.ajax({
                url: "prendre_rdv.php",
                type: "GET",
                success: function(response) {
                    $("#main-content").html(response); // Charger le contenu de prendre_rdv.php dans la section principale de la page
                },
                error: function(xhr, status, error) {
                    console.error("Erreur :", error);
                }
            });
        }

        function openAgenda() {
            $.ajax({
                url: "agendapat.php",
                type: "GET",
                success: function(response) {
                    $("#agenda-content").html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement de l'agenda :", error);
                }
            });
            console.log("Ouvrir l'agenda du patient");
        }
        function openHistorique() {
            window.location.href = "historique.php";
        }
        function openChat() {
            var chatAppUrl = 'http://localhost:4000';
        
        // Ouvrez le fichier index.html dans une nouvelle fenêtre
            window.open(chatAppUrl, '_blank');
        }
       
        function openOrdonnance() {
            $.ajax({
                url: "mesordo.php", // Correction de l'URL vers mesordo.php
                type: "GET",
                success: function(response) {
                    $("#agenda-content").html(response); // Charger le contenu de mesordo.php dans la section prévue
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement des ordonnances :", error);
                }
            });
}

            
    </script>
</body>
</html>
