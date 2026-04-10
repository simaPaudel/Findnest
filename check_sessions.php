<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=find_nest', 'root', '');
    $result = $pdo->query('SHOW TABLES LIKE "sessions"')->fetchAll();

    if (count($result) > 0) {
        echo "✅ Sessions table EXISTS\n";
        echo "Sessions table created successfully!\n";
    } else {
        echo "❌ Sessions table NOT found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
