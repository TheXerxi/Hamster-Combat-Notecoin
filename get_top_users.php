<?php
header('Content-Type: application/json');

// Połączenie z bazą danych
$dsn = 'mysql:host=mysqlHostHere;dbname=dbnamehere';
$username = 'dbUsername';
$password = 'dbpassword';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Przygotowanie zapytania SQL
    $sql_ranks = 'SELECT nickname, score FROM user ORDER BY score DESC LIMIT 10';
    $stmt = $conn->prepare($sql_ranks);

    // Wykonanie zapytania
    $stmt->execute();

    // Pobranie wyników zapytania
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Zwrot wyników jako JSON
    echo json_encode($results);

} catch (PDOException $e) {
    // Obsługa błędów połączenia z bazą danych
    http_response_code(500); // Ustaw status odpowiedzi na 500
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
