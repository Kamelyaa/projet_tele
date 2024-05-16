// Importer les modules nécessaires
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path'); // Importer le module path pour travailler avec les chemins de fichiers

// Créer une application Express
const app = express();
const server = http.createServer(app);

// Configurer Socket.IO pour écouter le serveur HTTP
const io = socketIo(server);

// Déclarer un tableau pour stocker les messages
let messages = [];

// Gérer les connexions Socket.IO
io.on('connection', (socket) => {
    console.log('Nouvelle connexion : ', socket.id);

    // Gérer la réception et la diffusion des messages
    socket.on('chat message', (message) => {
        console.log('Message reçu : ', message);

        // Ajouter le message au tableau des messages
        messages.push(message);

        // Diffuser le message à tous les clients connectés
        io.emit('chat message', message);
    });
});

// Définir le répertoire des fichiers statiques (HTML, CSS, JS)
app.use(express.static(path.join(__dirname, 'public')));

// Définir une route pour servir le fichier index.html
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Ajouter une route pour récupérer les messages via une API REST
app.get('/messages', (req, res) => {
    res.json(messages);
});

// Démarrer le serveur sur le port 3000
const PORT = process.env.PORT || 4000;
server.listen(PORT, () => {
    console.log(`Serveur en écoute sur le port ${PORT}`);
});

