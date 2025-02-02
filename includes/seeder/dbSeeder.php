<?php
$seederFiles = [
    'UserSeeder.php',
    'EventCategorySeeder.php',
    'EventSeeder.php'
];

foreach ($seederFiles as $file) {
    if (file_exists($file)) {
        echo "Running seeder: $file...<br>";
        require_once $file;
    } else {
        echo "❌ Error: Seeder file $file not found!<br>";
    }
}

echo "<br>✅ All seeders have been executed successfully!";
?>
