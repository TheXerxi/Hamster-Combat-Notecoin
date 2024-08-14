<?php
// Dane do połączenia z bazą danych
$host = 'dbhost'; // lub adres IP serwera bazy danych
$dbUsername = 'dbusername';
$dbPassword = 'dbpassword';
$dbName = 'dbname';

// Połączenie z bazą danych
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobranie username z parametru GET
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Zapytanie SQL, aby znaleźć id gracza na podstawie username
    $sql = "SELECT id FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Znaleziono gracza
        $row = $result->fetch_assoc();
        $id = $row['id'];

        // Teraz możesz pobrać wynik gracza na podstawie id
        $sql_score = "SELECT score FROM results WHERE player_id = $id";
        $result_score = $conn->query($sql_score);

        if ($result_score->num_rows > 0) {
            // Wypisz wynik gracza
            $row_score = $result_score->fetch_assoc();
            echo "Player's score: " . $row_score['score'];
        } else {
            echo "Player's score not found.";
        }
    } else {
        echo "Player not found.";
    }
} else {
    echo "Username parameter not provided.";
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
