<?php

require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();
}

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'persoonlijke_scan';
$db_user = getenv('DB_USER') ?: 'bit_academy';
$db_pass = getenv('DB_PASS') ?: 'bit_academy';

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    echo "Database verbinding mislukt:\n";
    echo $exception->getMessage() . "\n";
    exit(1);
}

echo "Verbonden met database: {$db_name}\n\n";

$migrationDir = __DIR__ . '/migrations';
$migrationFiles = glob($migrationDir . '/*.sql') ?: [];

natsort($migrationFiles);

if (empty($migrationFiles)) {
    echo "Geen migration bestanden gevonden in: {$migrationDir}\n";
    $conn->close();
    exit(0);
}

foreach ($migrationFiles as $migrationFile) {
    $fileName = basename($migrationFile);

    echo "Migration uitvoeren: {$fileName}\n";

    $sql = file_get_contents($migrationFile);

    if ($sql === false) {
        echo "Kon bestand niet lezen: {$fileName}\n";
        $conn->close();
        exit(1);
    }

    try {
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
        }

        if ($conn->errno) {
            throw new mysqli_sql_exception($conn->error, $conn->errno);
        }

        echo "Gelukt: {$fileName}\n\n";
    } catch (mysqli_sql_exception $exception) {
        echo "Fout in migration: {$fileName}\n";
        echo $exception->getMessage() . "\n";
        $conn->close();
        exit(1);
    }
}

$conn->close();

echo "Alle migrations zijn uitgevoerd.\n";