<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$activeTab = isset($_GET['tab']) && in_array($_GET['tab'], ['events', 'attendees']) ? $_GET['tab'] : 'events';

// Search Events
$events = [];
if (!empty($searchQuery)) {
    $stmt = $pdo->prepare("
        SELECT e.*, u.name as organizer 
        FROM events e
        JOIN users u ON e.created_by = u.id
        WHERE e.title LIKE ? OR e.location LIKE ?
    ");
    $stmt->execute(["%$searchQuery%", "%$searchQuery%"]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Search Attendees
$attendees = [];
if (!empty($searchQuery)) {
    $stmt = $pdo->prepare("
        SELECT a.*, e.title as event_title 
        FROM attendees a
        LEFT JOIN events e ON a.event_id = e.id
        WHERE a.name LIKE ? OR a.email LIKE ?
    ");
    $stmt->execute(["%$searchQuery%", "%$searchQuery%"]);
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function highlightSearchTerm($text, $searchTerm) {
    if (empty(trim($searchTerm))) return htmlspecialchars($text);
    
    $escapedSearch = preg_quote(htmlspecialchars($searchTerm), '/');
    return preg_replace(
        "/($escapedSearch)/i",
        '<span class="highlight bg-warning">$1</span>',
        htmlspecialchars($text)
    );
}

$pageTitle = "Search Results: " . htmlspecialchars($searchQuery);
include 'includes/header.php';
?>

<h2 class="mb-4">Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h2>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?= $activeTab === 'events' ? 'active' : '' ?>" 
            href="search.php?q=<?= urlencode($searchQuery) ?>&tab=events">
            Events (<?= count($events) ?>)
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $activeTab === 'attendees' ? 'active' : '' ?>" 
            href="search.php?q=<?= urlencode($searchQuery) ?>&tab=attendees">
            Attendees (<?= count($attendees) ?>)
        </a>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <!-- Events Tab -->
    <div class="tab-pane <?= $activeTab === 'events' ? 'show active' : '' ?>">
        <?php if (!empty($events)): ?>
            <div class="row">
                <?php foreach ($events as $event): ?>
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <?php if ($event['img']): ?>
                        <img src="uploads/<?= htmlspecialchars($event['img']) ?>" 
                                class="card-img-top" 
                                alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= highlightSearchTerm($event['title'], $searchQuery) ?></h5>
                            <p class="card-text">
                                <i class="bi bi-geo-alt"></i> <?= highlightSearchTerm($event['location'], $searchQuery) ?>
                            </p>
                            <a href="pages/events/view.php?id=<?= $event['id'] ?>" class="btn btn-primary">
                                View Event
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No matching events found</div>
        <?php endif; ?>
    </div>

    <!-- Attendees Tab -->
    <div class="tab-pane <?= $activeTab === 'attendees' ? 'show active' : '' ?>">
        <?php if (!empty($attendees)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Event</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendees as $attendee): ?>
                        <tr>
                            <td><?= highlightSearchTerm($attendee['name'], $searchQuery) ?></td>
                            <td><?= highlightSearchTerm($attendee['email'], $searchQuery) ?></td>
                            <td><?= htmlspecialchars($attendee['phone']) ?></td>
                            <td>
                                <?php if ($attendee['event_title']): ?>
                                    <?= htmlspecialchars($attendee['event_title']) ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, Y H:i', strtotime($attendee['registered_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No matching attendees found</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>