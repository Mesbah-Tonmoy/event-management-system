<?php
require_once 'connect.php';
require_once 'auth.php';  // To get is_logged_in() function

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$events_per_page = 6;
$offset = $page * $events_per_page;
$closest_event_id = isset($_POST['closest_event_id']) ? (int)$_POST['closest_event_id'] : 0;

$stmt = $pdo->prepare("
    SELECT e.*,
    (SELECT COUNT(*) FROM attendees WHERE event_id = e.id) AS registered_count,
    u.name as organizer, 
    ec.name as category_name
    FROM events e
    JOIN users u ON e.created_by = u.id
    LEFT JOIN event_categories ec ON e.category = ec.id
    WHERE e.date >= CURDATE()
    ". ($closest_event_id ? " AND e.id != :closest_event_id" : "")."
    ORDER BY e.date ASC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $events_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
if ($closest_event_id) {
    $stmt->bindValue(':closest_event_id', $closest_event_id, PDO::PARAM_INT);
}
$stmt->execute();

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '';
foreach ($events as $event) {
    $html .= '<div class="col-md-6 col-lg-4 mb-4">
        <div class="event-card">
            <a href="pages/events/view.php?id=' . $event['id'] . '">
                <img src="uploads/' . htmlspecialchars($event['img']) . '" alt="' . htmlspecialchars($event['title']) . '" class="event-image">
            </a>
            <div class="event-content">
                <div class="d-flex justify-content-between">
                    <h3 class="event-title">
                        <a href="pages/events/view.php?id=' . $event['id'] . '" class="text-decoration-none text-body">
                            ' . htmlspecialchars($event['title']) . '
                        </a>
                    </h3>
                    <span class="badge ' . ($event['registered_count'] >= $event['capacity'] ? 'bg-danger' : 'bg-primary') . ' mb-2">
                        ' . $event['registered_count'] . '/' . $event['capacity'] . '
                    </span>
                </div>
                <p class="event-date">Date: ' . date('M j, Y', strtotime($event['date'])) . '</p>';
                
    if (is_logged_in() && ($_SESSION['user_id'] == $event['created_by'] || is_admin())) {
        $html .= '<a href="pages/events/edit.php?id=' . $event['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                 <a href="pages/events/delete.php?id=' . $event['id'] . '" class="btn btn-sm btn-danger">Delete</a>';
    } else {
        $html .= '<a href="#" class="btn btn-sm register-btn btn-info" data-event-id="' . $event['id'] . '">Attend</a>';
    }
    
    $html .= '</div></div></div>';
}

// Return events HTML and other necessary data
header('Content-Type: application/json');
echo json_encode([
    'html' => $html,
    'count' => count($events)
]);