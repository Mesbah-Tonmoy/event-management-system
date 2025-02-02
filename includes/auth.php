<?php
function is_logged_in() {
    return isset($_SESSION['name']);
}

function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

function redirect_if_logged_in() {
    if (is_logged_in()) {
        header('Location: /evs-home/dashboard.php');
        exit;
    }
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: /evs/login.php');
        exit;
    }
}
?>