<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the theme configuration
require_once __DIR__ . '/../config/theme_config.php';

// Parameters
$username = $_GET['username'] ?? null;
$repository = $_GET['repository'] ?? null;
$theme = $_GET['theme'] ?? 'light';

// Optional custom colors
$bgColorHex = $_GET['bgColor'] ?? null;
$textColorHex = $_GET['textColor'] ?? null;
$borderColorHex = $_GET['borderColor'] ?? null;

// Validation: Ensure username and repository are provided
if (!$username || !$repository) {
    outputErrorImage("Missing Username or Repository");
}

// Validate the repository existence
if (!validateRepository($username, $repository)) {
    outputErrorImage("Invalid Repository: $username/$repository");
}

// File paths
$dataDir = __DIR__ . '/../data/';
$userFilePath = $dataDir . $username . '_repos.json';
$totalUsersFilePath = $dataDir . 'total_users.json';

// Initialize or load user data
$userData = [
    'total_views' => 0,
    'repositories' => [],
    'start_date' => date("Y-m-d H:i:s")
];

if (file_exists($userFilePath)) {
    $userData = json_decode(file_get_contents($userFilePath), true) ?? $userData;
} else {
    file_put_contents($userFilePath, json_encode($userData, JSON_PRETTY_PRINT));
}

// Validate repository in user's data
if (!isset($userData['repositories'][$repository])) {
    $userData['repositories'][$repository] = ['views' => 0];
}

// Increment views for the repository
$userData['repositories'][$repository]['views']++;

// Dynamically calculate total views
$userData['total_views'] = array_sum(array_column($userData['repositories'], 'views'));

// Save updated user data
file_put_contents($userFilePath, json_encode($userData, JSON_PRETTY_PRINT));

// Load total users data
$totalUsers = [];
if (file_exists($totalUsersFilePath)) {
    $totalUsers = json_decode(file_get_contents($totalUsersFilePath), true) ?? [];
}
if (!in_array("https://github.com/$username", $totalUsers)) {
    $totalUsers[] = "https://github.com/$username";
    file_put_contents($totalUsersFilePath, json_encode($totalUsers, JSON_PRETTY_PRINT));
}

// Get colors for the selected theme
$colors = getThemeColors($theme, $bgColorHex, $textColorHex, $borderColorHex);

// Generate the counter image
generateImage($username, $repository, $userData['repositories'][$repository]['views'], count($totalUsers), $colors);

/**
 * Validate if the repository exists by sending an HTTP HEAD request
 */
function validateRepository($username, $repository)
{
    $url = "https://github.com/$username/$repository";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

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

/**
 * Generate the counter image
 */
function generateImage($username, $repository, $views, $totalUsers, $colors)
{
    header("Content-Type: image/png");

    // Generate the text to be displayed
    $text = "$username/$repository | Views: $views";

    // Dynamically calculate the image width based on the text length
    $fontSize = 4; // Font size used in imagestring
    $textWidth = strlen($text) * imagefontwidth($fontSize); // Calculate width of the text
    $padding = 20; // Add some padding for aesthetics
    $width = $textWidth + $padding;

    // Set a fixed height for the image
    $height = 20;

    // Create the image
    $image = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($image, $colors['bgColor'][0], $colors['bgColor'][1], $colors['bgColor'][2]);
    $textColor = imagecolorallocate($image, $colors['textColor'][0], $colors['textColor'][1], $colors['textColor'][2]);
    $borderColor = imagecolorallocate($image, $colors['borderColor'][0], $colors['borderColor'][1], $colors['borderColor'][2]);

    // Fill background and border
    imagefill($image, 0, 0, $bgColor);
    imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

    // Add text to the image
    $textX = 10; // Fixed padding from the left
    $textY = ($height - imagefontheight($fontSize)) / 2; // Center the text vertically
    imagestring($image, $fontSize, $textX, $textY, $text, $textColor);

    // Output the image
    imagepng($image);
    imagedestroy($image);
}
