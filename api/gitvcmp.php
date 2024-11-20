<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Parameters
$username = $_GET['username'] ?? 'default';
$theme = $_GET['theme'] ?? 'light';
$layout = $_GET['layout'] ?? 'vertical';

// Optional custom colors
$bgColorHex = $_GET['bgColor'] ?? ($theme === 'dark' ? '222222' : 'FFFFFF');
$textColorHex = $_GET['textColor'] ?? ($theme === 'dark' ? 'FFFFFF' : '000000');
$borderColorHex = $_GET['borderColor'] ?? ($theme === 'dark' ? 'FFFFFF' : '000000');

// Convert hex colors to RGB
function hexToRgb($hex)
{
    $hex = str_replace("#", "", $hex);
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    ];
}

$bgColorRgb = hexToRgb($bgColorHex);
$textColorRgb = hexToRgb($textColorHex);
$borderColorRgb = hexToRgb($borderColorHex);

// File paths
$dataDir = __DIR__ . '/../data/';
$userFilePath = $dataDir . $username . '_repos.json';
$totalUsersFilePath = $dataDir . 'total_users.json';

// Initialize default user data
$userData = [
    'total_views' => 0,
    'repositories' => [],
    'start_date' => date("Y-m-d H:i:s")
];

// Load user-specific data
if (file_exists($userFilePath)) {
    $userData = json_decode(file_get_contents($userFilePath), true) ?? $userData;
} else {
    file_put_contents($userFilePath, json_encode($userData, JSON_PRETTY_PRINT));
}

// Ensure start_date exists
if (!isset($userData['start_date'])) {
    $userData['start_date'] = date("Y-m-d H:i:s");
}

// Calculate total views dynamically
$userData['total_views'] = array_sum(array_column($userData['repositories'], 'views'));

// Save updated user data
file_put_contents($userFilePath, json_encode($userData, JSON_PRETTY_PRINT));

// Load or initialize total_users.json
$totalUsers = [];
if (file_exists($totalUsersFilePath)) {
    $totalUsers = json_decode(file_get_contents($totalUsersFilePath), true) ?? [];
}
$totalUsersUsingCounter = count($totalUsers);

$userProfileUrl = "https://github.com/$username";
if (!in_array($userProfileUrl, $totalUsers)) {
    $totalUsers[] = $userProfileUrl;
    file_put_contents($totalUsersFilePath, json_encode($totalUsers, JSON_PRETTY_PRINT));
}

// Sort repositories by views (highest to lowest)
uasort($userData['repositories'], function ($a, $b) {
    return $b['views'] - $a['views'];
});

// Prepare the image
header("Content-Type: image/png");
$width = ($layout === 'horizontal') ? 500 : 400;
$height = 100 + (count($userData['repositories']) * 20) + 80; // Adjust height for additional text
$image = imagecreatetruecolor($width, $height);

// Colors
$bgColor = imagecolorallocate($image, $bgColorRgb[0], $bgColorRgb[1], $bgColorRgb[2]);
$textColor = imagecolorallocate($image, $textColorRgb[0], $textColorRgb[1], $textColorRgb[2]);
$borderColor = imagecolorallocate($image, $borderColorRgb[0], $borderColorRgb[1], $borderColorRgb[2]);

// Fill background and border
imagefill($image, 0, 0, $bgColor);
imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

// Display total views and repositories
imagestring($image, 5, 10, 10, "GitHubViewsCounter", $textColor);
imagestring($image, 4, 10, 40, "Profile: $username", $textColor);
imagestring($image, 4, 10, 60, "Total Views: " . $userData['total_views'], $textColor);

// Display repositories sorted by views
$y = 90;
imagestring($image, 4, 10, $y, "Repositories:", $textColor);
$y += 20;

foreach ($userData['repositories'] as $repoName => $repoData) {
    imagestring($image, 3, 10, $y, "$repoName:", $textColor);
    imagestring($image, 3, 200, $y, "{$repoData['views']} views", $textColor);
    $y += 20;
}

// Footer with total users and timestamps
$y += 10;
imagestring($image, 3, 10, $y, "Started on: {$userData['start_date']}", $textColor);
$y += 20;
imagestring($image, 3, 10, $y, "Last Updated: " . date("Y-m-d H:i:s"), $textColor);
$y += 20;

// Display total users
imagestring($image, 3, 10, $y, "Users using GitHubViewsCounter: $totalUsersUsingCounter", $textColor);

// Output the image
imagepng($image);
imagedestroy($image);
?>