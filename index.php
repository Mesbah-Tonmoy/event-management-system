<?php
    require_once 'includes/connect.php';
    require_once 'includes/auth.php';
    
    $pageTitle = "Event Management System";
    require_once 'includes/header.php';
    
    display_flash_messages();

    // For closest event query
    $stmt_closest = $pdo->query("
        SELECT e.*, 
        (SELECT COUNT(*) FROM attendees WHERE event_id = e.id) AS registered_count,
        u.name as organizer, 
        ec.name as category_name
        FROM events e
        JOIN users u ON e.created_by = u.id
        LEFT JOIN event_categories ec ON e.category = ec.id
        WHERE e.date >= CURDATE()
        ORDER BY e.date ASC
        LIMIT 1
    ");

    $closest_event = $stmt_closest->fetch(PDO::FETCH_ASSOC);
    
    // For upcoming events query
    $events_per_page = 6;
    $stmt_upcoming = $pdo->prepare("
        SELECT e.*,
        (SELECT COUNT(*) FROM attendees WHERE event_id = e.id) AS registered_count,
        u.name as organizer, 
        ec.name as category_name
        FROM events e
        JOIN users u ON e.created_by = u.id
        LEFT JOIN event_categories ec ON e.category = ec.id
        WHERE e.date >= CURDATE()
        ". ($closest_event ? " AND e.id != ".$closest_event['id'] : "")."
        ORDER BY e.date ASC
        LIMIT :limit
    ");
    $stmt_upcoming->bindValue(':limit', $events_per_page, PDO::PARAM_INT);
    $stmt_upcoming->execute();
    $upcoming_events = $stmt_upcoming->fetchAll(PDO::FETCH_ASSOC);

    // Get total count for pagination
    $stmt_count = $pdo->query("
        SELECT COUNT(*) as total
        FROM events e
        WHERE e.date >= CURDATE()
        ". ($closest_event ? " AND e.id != ".$closest_event['id'] : "")
    );
    $total_events = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];

    if ($closest_event): ?>
    <section class="hero-event py-sm-5" style="background-image: url('uploads/<?= htmlspecialchars($closest_event['img']) ?>');">
        <div class="container text-center bg-glass">
            <h2 id="hero-title" class="mb-4">
                <a href="pages/events/view.php?id=<?= $closest_event['id'] ?>" class="text-decoration-none text-white">
                    <span class="d-none d-sm-inline">Next Event: </span><?= htmlspecialchars($closest_event['title']) ?>
                </a>
            </h2>
            <div id="countdown" class="countdown mb-4" data-date="<?= $closest_event['date'] ?> <?= $closest_event['time'] ?>">
                <div class="countdown-section">
                    <span id="days"></span>
                    <p>Days</p>
                </div>
                <div class="countdown-section">
                    <span id="hours"></span>
                     <p>Hours</p>
                </div>
                <div class="countdown-section">
                    <span id="minutes"></span>
                    <p>Minutes</p>
                </div>
                <div class="countdown-section">
                    <span id="seconds"></span>
                     <p>Seconds</p>
                </div>
            </div>
            <p id="hero-description" class="lead mb-1">Join us for the ultimate <?= strtolower($closest_event['category_name']) ?> experience!</p>
            <p class="mb-4">Location: <?= htmlspecialchars($closest_event['location']) ?></p>
            <?php if (is_logged_in() && ($_SESSION['user_id'] == $closest_event['created_by'] || is_admin())): ?>
                <a href="pages/events/edit.php?id=<?= $closest_event['id'] ?>" class="btn btn-register btn-lg">Edit Event</a>
            <?php else: ?>
                <a href="#" class="btn btn-register btn-lg register-btn" data-event-id="<?= $closest_event['id'] ?>">
                    Register Now
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php else: ?>
    <div class="container text-center bg-glass">
        <h2 class="mb-4">No upcoming events found</h2>
    </div>
    <?php endif; ?>

    <section class="other-events pt-5 pb-2 pb-lg-5">
        <div class="container">
            <h2 class="text-center mb-4">Upcoming Events</h2>
            <div class="row">
                <?php if (!empty($upcoming_events)): ?>
                    <?php foreach ($upcoming_events as $event): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="event-card">
                            <a href="pages/events/view.php?id=<?= $event['id'] ?>">
                                <img src="uploads/<?= htmlspecialchars($event['img']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="event-image">
                            </a>
                            <div class="event-content">
                                <div class="d-flex justify-content-between">
                                    <h3 class="event-title">
                                        <a href="pages/events/view.php?id=<?= $event['id'] ?>" class="text-decoration-none text-body">
                                            <?= htmlspecialchars($event['title']) ?>
                                        </a>
                                    </h3>
                                    <span class="badge <?= $event['registered_count'] >= $event['capacity'] ? 'bg-danger' : 'bg-primary' ?> mb-2">
                                        <?= $event['registered_count'] ?>/<?= $event['capacity'] ?>
                                    </span>
                                </div>
                                <p class="event-date">Date: <?= date('M j, Y', strtotime($event['date'])) ?></p>
                                <?php if (is_logged_in() && ($_SESSION['user_id'] == $event['created_by'] || is_admin())): ?>
                                    <a href="pages/events/edit.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="pages/events/delete.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                                <?php else: ?>
                                <a href="#" class="btn btn-sm register-btn btn-info" 
                                data-event-id="<?= $event['id'] ?>">
                                    Attend
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No other upcoming events</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($total_events > count($upcoming_events)): ?>
                <div class="text-center mt-4">
                    <button id="loadMoreBtn" class="btn btn-primary" data-event-id="<?= $closest_event['id'] ?>" data-page="1" data-total="<?= $total_events ?>">
                        Load More
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once 'includes/modal.php'; ?>
    <?php require_once 'includes/footer.php'; ?>