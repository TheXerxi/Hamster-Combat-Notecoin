<?php
// Dane do połączenia z bazą danych
$host = 'mysql host'; // lub adres IP serwera bazy danych
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

// Pobranie zadań do wykonania
$sql = "SELECT id, title, description, action_url, points FROM tasks WHERE status = 'pending'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $tasks = array();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    echo json_encode(['success' => true, 'tasks' => $tasks]);
} else {
    echo json_encode(['success' => false, 'message' => 'No tasks found.']);
}
$conn->close();
?>
