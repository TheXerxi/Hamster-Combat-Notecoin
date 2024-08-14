
<?php
// Dane do połączenia z bazą danych
$host = 'dbHostHere'; // lub adres IP serwera bazy danych
$dbUsername = 'dbUserName';
$dbPassword = 'dbPassword';
$dbName = 'dbNameHere';

// Połączenie z bazą danych
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobranie id gracza z parametru GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Zapytanie SQL, aby znaleźć wynik gracza na podstawie id
    $sql_score = "SELECT score FROM user WHERE telegramId = $id";
    $result_score = $conn->query($sql_score);

    if ($result_score->num_rows > 0) {
        // Wypisz wynik gracza
        $row_score = $result_score->fetch_assoc();

        // Zapytanie SQL, aby znaleźć username gracza na podstawie id
        $sql_username = "SELECT nickname FROM user WHERE telegramId = $id";
        $result_username = $conn->query($sql_username);

        if ($result_username->num_rows > 0) {
            // Wypisz powitanie z username
            $row_username = $result_username->fetch_assoc();
            echo "Hello, " . $row_username['username'] . "! Your score is: " . $row_score['score'];
        } else {
            echo "Player's username not found.";
        }
    } else {
        echo "Player's score not found.";
    }
} else {
    echo "Player id parameter not provided.";
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
