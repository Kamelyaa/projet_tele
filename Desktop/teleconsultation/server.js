const express = require('express');
const http = require('http');
const path = require('path');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(express.static(path.join(__dirname, 'public')));

let connectedPeers = new Map();
let currentSessionID = null;

io.on('connection', socket => {
    console.log('Un client s\'est connecté');

    socket.on('join-session', () => {
        if (currentSessionID) {
            console.log('Nouveau client rejoint la session existante');
            connectedPeers.set(socket, currentSessionID);
            socket.emit('session-joined', currentSessionID);
            io.emit('new-client-joined'); // Diffuser l'événement à tous les clients
        } else {
            console.log('Création d\'une nouvelle session');
            currentSessionID = socket.id;
            connectedPeers.set(socket, currentSessionID);
            socket.emit('session-created', currentSessionID);
        }
    });

    socket.on('offer', offer => {
        socket.broadcast.emit('offer', offer);
    });

    socket.on('answer', answer => {
        socket.broadcast.emit('answer', answer);
    });

    socket.on('ice-candidate', candidate => {
        socket.broadcast.emit('ice-candidate', candidate);
    });

    socket.on('disconnect', () => {
        console.log('Un client s\'est déconnecté');
        connectedPeers.delete(socket);
        if (connectedPeers.size === 0) {
            currentSessionID = null;
        }
    });
});

server.listen(3000, () => {
    console.log('Serveur démarré sur le port 3000');
});

