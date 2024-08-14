window.addEventListener('DOMContentLoaded', (event) => {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('userId');

    fetch(`/api/user?userId=${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('nickname').innerText = data.nickname;
            document.getElementById('score').innerText = data.score;
        })
        .catch(err => {
            console.error('Błąd:', err);
        });
});
