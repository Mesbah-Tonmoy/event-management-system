<?php
require_once '../connect'; // Adjust the path to your DB connection

class EventSeeder {
    public static function run($pdo) {
        $events = [
            [
                'title' => 'Tech Conference 2025',
                'description' => 'A conference for tech enthusiasts and professionals.',
                'img' => 'tech-conference.webp',
                'date' => '2025-03-15',
                'time' => '09:00:00',
                'location' => 'Tech Center, Silicon Valley',
                'capacity' => 200,
                'created_by' => 1,
                'category' => 1 // Conference
            ],
            [
                'title' => 'AI Innovations Workshop',
                'description' => 'Learn about the latest AI advancements in this hands-on workshop.',
                'img' => 'ai-workshop.jpg',
                'date' => '2025-04-20',
                'time' => '13:00:00',
                'location' => 'AI Hub, New York',
                'capacity' => 50,
                'created_by' => 2,
                'category' => 2 // Workshop
            ],
            [
                'title' => 'Cybersecurity Webinar',
                'description' => 'A deep dive into cybersecurity threats and solutions.',
                'img' => 'cybersecurity-webinar.jpg',
                'date' => '2025-05-10',
                'time' => '18:00:00',
                'location' => 'Online - Zoom',
                'capacity' => 500,
                'created_by' => 1,
                'category' => 3 // Webinar
            ],
            [
                'title' => 'Business Networking Night',
                'description' => 'An exclusive networking event for professionals across industries.',
                'img' => 'networking-night.jpg',
                'date' => '2025-06-05',
                'time' => '19:00:00',
                'location' => 'Grand Hotel, Los Angeles',
                'capacity' => 150,
                'created_by' => 2,
                'category' => 4 // Networking
            ],
            [
                'title' => 'Marketing Strategies Seminar',
                'description' => 'A seminar on effective marketing strategies in the digital age.',
                'img' => 'marketing-seminar.jpg',
                'date' => '2025-07-12',
                'time' => '10:00:00',
                'location' => 'Marketing Hub, Chicago',
                'capacity' => 100,
                'created_by' => 1,
                'category' => 5 // Seminar
            ],
            [
                'title' => 'International Tech Exhibition',
                'description' => 'Explore the latest technology innovations and startups.',
                'img' => 'tech-exhibition.jpg',
                'date' => '2025-08-18',
                'time' => '09:00:00',
                'location' => 'Expo Center, San Francisco',
                'capacity' => 1000,
                'created_by' => 1,
                'category' => 6 // Exhibition
            ],
            [
                'title' => 'Healthcare Conference 2025',
                'description' => 'Discussions on the future of healthcare and technology.',
                'img' => 'healthcare-conference.jpg',
                'date' => '2025-09-05',
                'time' => '08:30:00',
                'location' => 'Medical Center, Boston',
                'capacity' => 300,
                'created_by' => 1,
                'category' => 1 // Conference
            ],
            [
                'title' => 'Python Programming Workshop',
                'description' => 'An interactive workshop on Python programming.',
                'img' => 'python-workshop.jpg',
                'date' => '2025-10-02',
                'time' => '14:00:00',
                'location' => 'Code Academy, Seattle',
                'capacity' => 40,
                'created_by' => 2,
                'category' => 2 // Workshop
            ],
            [
                'title' => 'Remote Work Webinar',
                'description' => 'A webinar on best practices for remote work and productivity.',
                'img' => 'remote-work-webinar.jpg',
                'date' => '2025-11-08',
                'time' => '17:00:00',
                'location' => 'Online - Webex',
                'capacity' => 600,
                'created_by' => 1,
                'category' => 3 // Webinar
            ],
            [
                'title' => 'Startup Founders Networking Event',
                'description' => 'A networking event for startup founders and investors.',
                'img' => 'startup-networking.jpg',
                'date' => '2025-12-01',
                'time' => '18:00:00',
                'location' => 'Venture Hub, San Diego',
                'capacity' => 120,
                'created_by' => 1,
                'category' => 4 // Networking
            ],
            [
                'title' => 'Entrepreneurship Seminar',
                'description' => 'Learn from successful entrepreneurs about starting a business.',
                'img' => 'entrepreneurship-seminar.jpg',
                'date' => '2026-01-20',
                'time' => '09:30:00',
                'location' => 'Business School, Houston',
                'capacity' => 80,
                'created_by' => 1,
                'category' => 5 // Seminar
            ],
            [
                'title' => 'Auto Expo 2026',
                'description' => 'A grand exhibition of the latest cars and automotive technology.',
                'img' => 'auto-expo.jpg',
                'date' => '2026-02-15',
                'time' => '10:00:00',
                'location' => 'Convention Center, Detroit',
                'capacity' => 5000,
                'created_by' => 1,
                'category' => 6 // Exhibition
            ],
            [
                'title' => 'AI and Machine Learning Conference',
                'description' => 'Experts discuss the future of AI and ML.',
                'img' => 'ai-conference.jpg',
                'date' => '2026-03-22',
                'time' => '09:00:00',
                'location' => 'Innovation Park, Toronto',
                'capacity' => 250,
                'created_by' => 1,
                'category' => 1 // Conference
            ],
            [
                'title' => 'UI/UX Design Workshop',
                'description' => 'A workshop for designers on creating better user experiences.',
                'img' => 'uiux-workshop.jpg',
                'date' => '2026-04-18',
                'time' => '11:00:00',
                'location' => 'Design Hub, Berlin',
                'capacity' => 30,
                'created_by' => 2,
                'category' => 2 // Workshop
            ],
            [
                'title' => 'Digital Marketing Strategies Webinar',
                'description' => 'A webinar on digital marketing trends for businesses.',
                'img' => 'digital-marketing-webinar.webp',
                'date' => '2026-05-12',
                'time' => '16:00:00',
                'location' => 'Online - Google Meet',
                'capacity' => 700,
                'created_by' => 1,
                'category' => 3 // Webinar
            ]            
        ];

        foreach ($events as $event) {
            $stmt = $pdo->prepare("INSERT INTO events (title, description, img, date, time, location, capacity, created_by, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $event['title'],
                $event['description'],
                $event['img'],
                $event['date'],
                $event['time'],
                $event['location'],
                $event['capacity'],
                $event['created_by'],
                $event['category']
            ]);
        }

        echo "Events seeded successfully!";
    }
}

// Run the seeder
EventSeeder::run($pdo);