<?php
require 'db.php'; // Database connection file

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = (int)$_GET['id'];

// Fetch the ad link
$stmt = $db->prepare("SELECT link FROM ads WHERE id = ?");
$stmt->execute([$id]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ad) {
    die("Ad not found.");
}

// Update click count
$db->prepare("UPDATE ads SET clicks = clicks + 1 WHERE id = ?")->execute([$id]);

// Redirect to the actual ad link
header("Location: " . $ad['link']);
exit;
