<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the theme configuration
require_once __DIR__ . '/../config/theme_config.php';

// Parameters
$username = $_GET['username'] ?? null;
$theme = $_GET['theme'] ?? 'light';
$layout = $_GET['layout'] ?? 'vertical';

// Optional custom colors
$bgColorHex = $_GET['bgColor'] ?? null;
$textColorHex = $_GET['textColor'] ?? null;
$borderColorHex = $_GET['borderColor'] ?? null;

// Validate username existence
if (!$username || !validateUsername($username)) {
    outputErrorImage("Invalid Username: $username");
}

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

// Dynamically calculate total views
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

// Get colors for the selected theme
$colors = getThemeColors($theme, $bgColorHex, $textColorHex, $borderColorHex);

// Prepare the image
header("Content-Type: image/png");
$width = ($layout === 'horizontal') ? 500 : 400;
$height = 100 + (count($userData['repositories']) * 20) + 80; // Adjust height for additional text
$image = imagecreatetruecolor($width, $height);

// Colors
$bgColor = imagecolorallocate($image, $colors['bgColor'][0], $colors['bgColor'][1], $colors['bgColor'][2]);
$textColor = imagecolorallocate($image, $colors['textColor'][0], $colors['textColor'][1], $colors['textColor'][2]);
$borderColor = imagecolorallocate($image, $colors['borderColor'][0], $colors['borderColor'][1], $colors['borderColor'][2]);

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

/**
 * Validate if the username exists by sending an HTTP HEAD request to the GitHub profile
 */
function validateUsername($username)
{
    $url = "https://github.com/$username";

    // Initialize a cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Return true if the profile exists (HTTP 200)
    return $httpCode === 200;
}

/**
 * Output an error image
 */
function outputErrorImage($message)
{
    header("Content-Type: image/png");
    $image = imagecreatetruecolor(400, 100);
    $bgColor = imagecolorallocate($image, 255, 0, 0);
    $textColor = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $bgColor);
    imagestring($image, 5, 10, 10, "Error:", $textColor);
    imagestring($image, 4, 10, 40, $message, $textColor);
    imagepng($image);
    imagedestroy($image);
    exit();
}
