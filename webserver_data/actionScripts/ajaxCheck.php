<?php

if (function_exists('isAllowedRequest')) return;

function isAllowedRequest(): bool {
    // 1. Interner PHP-Include kein HTTP-Request
    if (!isset($_SERVER['REQUEST_METHOD'])) {
        return true;
    }

    // 2. AJAX-Request (fetch / XMLHttpRequest)
    $xrw = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    if (strtolower($xrw) === 'xmlhttprequest') {
        return true;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
        return true;
    }

    return false;
}

if (!isAllowedRequest()) {
    header('Location: /unauthorized.php');
    exit;
}