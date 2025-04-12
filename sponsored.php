<?php
header("Content-Type: application/javascript");

    require 'db.php'; // Database connection file



if (!isset($_GET['id'])) {
    exit;
}

$id = $_GET['id'];

// Fetch the ad
$stmt = $db->prepare("SELECT * FROM ads WHERE slug = ? AND status = 1 AND display_from <= ? AND display_to >= ?");
$today = date('Y-m-d'); // Get today's date
$stmt->execute([$id, $today, $today]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ad) {
    exit;
}

// Detect if the user is on mobile
$isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $_SERVER['HTTP_USER_AGENT']);
$image = $isMobile ? $ad['mobile_image'] : $ad['desktop_image'];
$image = BASE_URL.$image;

// Increment impressions
$success = $db->prepare("UPDATE ads SET impressions = impressions + 1 WHERE id = ?")->execute([$id]);

$base_url = BASE_URL;
// Output JavaScript code to insert the ad dynamically
echo "
(function() {
    var adContainer = document.createElement('div');
    adContainer.style.textAlign = 'center';
    adContainer.style.margin = '10px 0';

    var adLink = document.createElement('a');
    adLink.href = '{$base_url}click?id={$id}';
    adLink.target = '_blank';
   
    var adImage = document.createElement('img');
    adImage.src = '{$image}';
    adImage.style.maxWidth = '100%';
    adImage.style.height = 'auto';
    adImage.style.cursor = 'pointer';
    adImage.style.border = '1px solid #ddd';

    

    adLink.appendChild(adImage);
    adContainer.appendChild(adLink);
    
    document.currentScript.parentNode.insertBefore(adContainer, document.currentScript);
})();
";
?>
