const express = require('express');
const path = require('path');
const dotenv = require('dotenv');
const mongoose = require('mongoose');
const { Telegraf } = require('telegraf');

// Wczytanie zmiennych środowiskowych z pliku .env
dotenv.config();

// Inicjalizacja Express.js
const app = express();
const PORT = process.env.PORT || 3000;

// Ustawienie folderu statycznego dla plików publicznych
app.use(express.static(path.join(__dirname, 'public')));

// Ustawienie silnika szablonów ejs
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views')); // Ustawienie ścieżki do folderu z widokami

// Połączenie z bazą danych MongoDB
mongoose.connect(process.env.MONGO_URI, { useNewUrlParser: true, useUnifiedTopology: true })
    .then(() => console.log('Connected to MongoDB'))
    .catch(err => console.error('MongoDB connection error:', err));

// Model użytkownika w bazie danych
const User = mongoose.model('User', {
    telegramId: Number,
    nickname: String,
    score: Number
});

// Inicjalizacja bota Telegram
const bot = new Telegraf(process.env.BOT_TOKEN);

// Obsługa komendy /start w bocie Telegram
bot.start(async (ctx) => {
    const userId = ctx.from.id;
    const username = ctx.from.username || ctx.from.first_name;

    // Znajdź lub utwórz użytkownika w bazie danych
    let user = await User.findOne({ telegramId: userId });
    if (!user) {
        user = new User({ telegramId: userId, nickname: username, score: 0 });
        await user.save();
    }

    // URL do gry z parametrem userId
    const gameUrl = `${process.env.SERVER_URL}/game?userId=${userId}`;

    // Odpowiedź użytkownikowi z przyciskiem do gry
    await ctx.reply('Click Below to play a game:', {
        reply_markup: {
            inline_keyboard: [
                [{ text: 'Play', url: gameUrl }]
            ]
        }
    });
});

// Uruchomienie bota Telegram
bot.launch().then(() => {
    console.log('Bot Telegram started!');
}).catch((err) => {
    console.error('Error when opening Telegram:', err);
});

// Endpoint do renderowania strony game.html
app.get('/game', async (req, res) => {
    const userId = req.query.userId;

    // Znajdź użytkownika w bazie danych po telegramId
    const user = await User.findOne({ telegramId: userId });
    if (!user) {
        return res.status(404).send('User not found');
    }

    // Renderowanie strony HTML z przekazanymi danymi
    res.render('game', { username: user.nickname, score: user.score });
});

// Start serwera Express.js
app.listen(PORT, () => {
    console.log(`Server work on port ${PORT}`);
});
