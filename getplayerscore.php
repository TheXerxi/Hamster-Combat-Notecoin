<?php
// Dane do połączenia z bazą danych
$host = 'host'; // lub adres IP serwera bazy danych
$dbUsername = 'dbusername';
$dbPassword = 'dbpassword';
$dbName = 'dbname';

// Połączenie z bazą danych
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ustawienie nagłówka HTTP dla JSON
header('Content-Type: application/json');

// Pobranie id gracza z parametru GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Sprawdzenie, czy należy zwiększyć wynik
    if (isset($_GET['action']) && $_GET['action'] === 'increase') {
        // Zapytanie SQL, aby zwiększyć wynik gracza o 1
        $sql_increase = "UPDATE user SET score = score + 1 WHERE telegramId = ?";
        $stmt = $conn->prepare($sql_increase);
        $stmt->bind_param('s', $id);
        $stmt->execute();
    }

    // Zapytanie SQL, aby znaleźć wynik gracza na podstawie id
    $sql_score = "SELECT score, nickname FROM user WHERE telegramId = ?";
    $stmt = $conn->prepare($sql_score);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result_score = $stmt->get_result();

    if ($result_score->num_rows > 0) {
        $row_score = $result_score->fetch_assoc();

        // Zwrócenie wyników jako JSON
        echo json_encode(array(
            'nickname' => $row_score['nickname'],
            'score' => $row_score['score']
        ));
    } else {
        echo json_encode(array(
            'error' => "Player not found."
        ));
    }
} else {
    echo json_encode(array(
        'error' => "Player id parameter not provided."
    ));
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
