<?php

/**
 * Database Configuration Test
 * This file checks if the database is configured correctly
 */

require __DIR__ . '/../src/utils/autoloader.php';

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css'>
    <title>Database Test | RunTracker</title>
    <style>
        .success { color: #2ecc71; }
        .error { color: #e74c3c; }
        .info { color: #3498db; }
        pre { background: #f4f4f4; padding: 1rem; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <main class='container'>
        <h1>🔍 Database Configuration Test</h1>
        <hr>
";

// Test 1: Check if config file exists
echo "<h2>Test 1: Configuration File</h2>";
$configFile = __DIR__ . '/../src/config/database.ini';
if (file_exists($configFile)) {
    echo "<p class='success'>✓ Configuration file exists: <code>$configFile</code></p>";
    $config = parse_ini_file($configFile, true);
    echo "<pre>";
    echo "Host: " . ($config['host'] ?? 'N/A') . "\n";
    echo "Port: " . ($config['port'] ?? 'N/A') . "\n";
    echo "Database: " . ($config['database'] ?? 'N/A') . "\n";
    echo "Username: " . ($config['username'] ?? 'N/A') . "\n";
    echo "Password: " . (isset($config['password']) ? str_repeat('*', strlen($config['password'])) : 'N/A');
    echo "</pre>";
} else {
    echo "<p class='error'>✗ Configuration file not found!</p>";
}

echo "<hr>";

// Test 2: Database connection
echo "<h2>Test 2: Database Connection</h2>";
try {
    $db = new Database();
    echo "<p class='success'>✓ Database connection successful!</p>";

    // Test 3: Get PDO instance
    echo "<hr>";
    echo "<h2>Test 3: PDO Instance</h2>";
    $pdo = $db->getPdo();
    if ($pdo instanceof PDO) {
        echo "<p class='success'>✓ PDO instance created successfully</p>";
        echo "<p class='info'>PDO Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "</p>";
    } else {
        echo "<p class='error'>✗ PDO instance not valid</p>";
    }

    // Test 4: Check database exists
    echo "<hr>";
    echo "<h2>Test 4: Database Existence</h2>";
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && $result['db_name']) {
        echo "<p class='success'>✓ Connected to database: <strong>{$result['db_name']}</strong></p>";
    } else {
        echo "<p class='error'>✗ No database selected</p>";
    }

    // Test 5: Check tables
    echo "<hr>";
    echo "<h2>Test 5: Tables Structure</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($tables) > 0) {
        echo "<p class='success'>✓ Found " . count($tables) . " table(s):</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li><strong>$table</strong>";

            // Get table structure
            $stmt = $pdo->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>";
            foreach ($columns as $column) {
                echo sprintf(
                    "  %-15s %-20s %-5s %-5s %s\n",
                    $column['Field'],
                    $column['Type'],
                    $column['Null'],
                    $column['Key'],
                    $column['Extra']
                );
            }
            echo "</pre>";

            // Count rows
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM `$table`");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p class='info'>Rows in table: {$count['count']}</p>";

            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>✗ No tables found in database</p>";
    }

    // Test 6: Test insert capability (optional - will rollback)
    echo "<hr>";
    echo "<h2>Test 6: Write Permissions</h2>";
    try {
        $pdo->beginTransaction();

        // Try to insert a test user
        $testUsername = 'test_user_' . time();
        $testEmail = 'test_' . time() . '@example.com';
        $testPassword = password_hash('test123', PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$testUsername, $testEmail, $testPassword]);

        echo "<p class='success'>✓ Write permissions OK (test insert successful)</p>";

        // Rollback - don't actually save the test data
        $pdo->rollBack();
        echo "<p class='info'>Test data rolled back (not saved)</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p class='error'>✗ Write test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

    echo "<hr>";
    echo "<h2>✅ Overall Result</h2>";
    echo "<p class='success' style='font-size: 1.2em;'><strong>Database is configured correctly!</strong></p>";
    echo "<p><a href='index.php'><button>← Back to Home</button></a></p>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Database connection failed!</p>";
    echo "<pre class='error'>";
    echo "Error: " . htmlspecialchars($e->getMessage()) . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine();
    echo "</pre>";

    echo "<hr>";
    echo "<h2>❌ Overall Result</h2>";
    echo "<p class='error' style='font-size: 1.2em;'><strong>Database configuration has errors!</strong></p>";
    echo "<p>Please check the error message above and fix the configuration.</p>";
}

echo "
    </main>
</body>
</html>";
