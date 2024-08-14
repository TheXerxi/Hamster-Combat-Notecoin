require('dotenv').config();
const { Telegraf, Markup } = require('telegraf');
const express = require('express');
const mongoose = require('mongoose');

// Connect to MongoDB
mongoose.connect(process.env.MONGO_URI, { useNewUrlParser: true, useUnifiedTopology: true })
    .then(() => console.log('Connected to MongoDB'))
    .catch(err => console.error('MongoDB connection error:', err));

// User model
const User = require('./models/User');

// Initialize bot
const bot = new Telegraf(process.env.BOT_TOKEN);

// Handle /start command
bot.start(async (ctx) => {
    const userId = ctx.from.id;
    const username = ctx.from.username || ctx.from.first_name;

    // Find or create user in the database
    let user = await User.findOne({ telegramId: userId });
    if (!user) {
        user = new User({ telegramId: userId, nickname: username, score: 0 });
        await user.save();
    }

    const gameUrl = `${process.env.SERVER_URL}/game/game.html`;

    ctx.reply('Click to play below:', Markup.inlineKeyboard([
        Markup.button.webApp('Play', gameUrl)
    ]));
});

// Start the bot
bot.launch().then(() => {
    console.log('Bot working!');
}).catch((err) => {
    console.error('Error connecting bot:', err);
});

// Enable graceful stop
process.once('SIGINT', () => bot.stop('SIGINT'));
process.once('SIGTERM', () => bot.stop('SIGTERM'));
