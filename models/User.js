const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
    telegramId: {
        type: Number,
        required: true,
        unique: true
    },
    nickname: {
        type: String,
        required: true
    },
    score: {
        type: Number,
        default: 0
    }
});

const User = mongoose.model('User', userSchema);
module.exports = User;
