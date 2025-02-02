<?php
require_once '../connect'; // Adjust the path to your DB connection

class UserSeeder {
    public static function run($pdo) {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@evs.com',
                'password' => password_hash('admin_01', PASSWORD_DEFAULT),
                'is_admin' => 1
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@evs.com',
                'password' => password_hash('user_01', PASSWORD_DEFAULT),
                'is_admin' => 0
            ]
        ];

        foreach ($users as $user) {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $user['name'],
                $user['email'],
                $user['password'],
                $user['is_admin'],
            ]);
        }

        echo "✅ Users seeded successfully!<br>";
    }
}

// Run the seeder
UserSeeder::run($pdo);