<?php
// Ustawienia bazy danych
$host = 'mysqlhost';
$dbUsername = 'dbUserName';
$dbPassword = 'dbPassword';
$dbName = 'DbName';

// Ustawienia nagłówków odpowiedzi
header('Content-Type: application/json');

// Połączenie z bazą danych
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    error_log('Connection failed: ' . $conn->connect_error);
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Przetwarzanie żądania POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Sprawdzenie czy JSON został poprawnie zdekodowany
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input: ' . json_last_error_msg()]);
        exit;
    }

    // Walidacja danych wejściowych
    if (isset($data['task_id']) && isset($data['telegramId'])) {
        $taskId = intval($data['task_id']);  // Konwersja na int
        $telegramId = intval($data['telegramId']); // Konwersja na int

        // Sprawdzenie, czy wpis istnieje i kiedy został zaktualizowany
        $sql_check_user_task = "
            SELECT completed_at 
            FROM user_tasks 
            WHERE telegramId = ? AND task_id = ?";
        
        $stmt_check_user_task = $conn->prepare($sql_check_user_task);
        
        if ($stmt_check_user_task) {
            $stmt_check_user_task->bind_param("ii", $telegramId, $taskId);
            if ($stmt_check_user_task->execute()) {
                $stmt_check_user_task->bind_result($completedAt);
                $stmt_check_user_task->fetch();
                
                if ($completedAt !== null) {
                    // Obliczenie różnicy czasu
                    $currentTime = time();
                    $completedTime = strtotime($completedAt);
                    $timeDifference = $currentTime - $completedTime;

                    // Sprawdzenie, czy minęły 2 godziny (7200 sekund)
                    if ($timeDifference >= 7200) {
                        // Aktualizacja wyniku użytkownika o 10000 punktów
                        $updateSql = "UPDATE user SET score = score + 10000 WHERE telegramId = ?";
                        $updateStmt = $conn->prepare($updateSql);

                        if ($updateStmt) {
                            $updateStmt->bind_param("i", $telegramId);
                            $updateStmt->execute()
                            $updateStmt->close();
                       }};
$conn->close();
?>
