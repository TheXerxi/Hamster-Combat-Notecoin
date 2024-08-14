<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Score</title>
</head>
<body>
    <div>
        <h1>Player Score</h1>
        <form action="" method="GET">
            <label for="username">Enter username:</label>
            <input type="text" id="username" name="username" required>
            <button type="submit">Get Score</button>
        </form>
        <div>
            <?php
            // Dane do połączenia z bazą danych
            $host = 'dbHost'; // lub adres IP serwera bazy danych
            $dbUsername = 'dbUserName';
            $dbPassword = 'dbPassword';
            $dbName = 'dbName';
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
                $sql = "SELECT id FROM players WHERE username = '$username'";
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
                        echo "<p>Player's score: " . $row_score['score'] . "</p>";
                    } else {
                        echo "<p>Player's score not found.</p>";
                    }
                } else {
                    echo "<p>Player not found.</p>";
                }
            } else {
                echo "<p>Username parameter not provided.</p>";
            }

            // Zamknięcie połączenia z bazą danych
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
