<?php
// Połączenie z bazą danych
$servername = "hostname";
$username = "username";
$password = "password";
$dbname = "dbname";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobranie ID z URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Sprawdzenie czy ID jest prawidłowe
if ($user_id > 0) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Wyświetlenie danych użytkownika
        $row = $result->fetch_assoc();
        echo "ID: " . $row["id"] . "<br>";
        echo "Imię: " . $row["name"] . "<br>";
        echo "Email: " . $row["email"] . "<br>";
        echo "Wynik: " . $row["score"] . "<br>";
    } else {
        echo "Nie znaleziono użytkownika.";
    }

    $stmt->close();
} else {
    echo "Nieprawidłowe ID.";
}

$conn->close();
?>
