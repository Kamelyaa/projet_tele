const express = require('express');
const app = express();

app.get('/', (req, res) => {
    res.send('Bienvenue sur mon serveur !');
});

app.listen(3000, () => {
    console.log('Serveur démarré sur le port 3000');
});

