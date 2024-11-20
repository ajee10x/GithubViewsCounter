<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Parameters
$username = $_GET['username'] ?? 'default';
$repository = $_GET['repository'] ?? 'default-repo';
$theme = $_GET['theme'] ?? 'light';
$layout = $_GET['layout'] ?? 'horizontal';

// File paths
$dataDir = __DIR__ . '/../data/';
$userFilePath = $dataDir . $username . '_repos.json';
$totalUsersFilePath = $dataDir . 'total_users.json';

// Initialize default user data
$userData = [
    'total_views' => 0,
    'repositories' => [],
    'start_date' => date("Y-m-d H:i:s") // Automatically set start date on first access
];

// Load or initialize user-specific data
if (file_exists($userFilePath)) {
    $userData = json_decode(file_get_contents($userFilePath), true) ?? $userData;
} else {
    file_put_contents($userFilePath, json_encode($userData, JSON_PRETTY_PRINT));
}

// Ensure the repository exists in the user data
if (!isset($userData['repositories'][$repository])) {
    $userData['repositories'][$repository] = [
        'views' => 0
    ];
}

// Increment views for the current repository
$userData['repositories'][$repository]['views']++;

// Dynamically calculate total views
$userData['total_views'] = array_sum(array_column($userData['repositories'], 'views'));

// Save the updated user data back to the JSON file
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

// Prepare the compact counter image
$width = ($layout === 'horizontal') ? 300 : 200; // Smaller width
$height = ($layout === 'horizontal') ? 100 : 150; // Adjust height based on layout
$image = imagecreatetruecolor($width, $height);

// Colors
$bgColor = imagecolorallocate($image, 240, 240, 240);
$textColor = imagecolorallocate($image, 0, 0, 0);
$borderColor = imagecolorallocate($image, 100, 100, 100);

// Fill background and border
imagefill($image, 0, 0, $bgColor);
imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

// Display repository-specific information
imagestring($image, 4, 10, 10, "$username/$repository", $textColor);
imagestring($image, 4, 10, 40, "Views: " . $userData['repositories'][$repository]['views'], $textColor);

// Footer with total users
imagestring($image, 3, 10, $height - 20, "Users using GithubViewsCounter: $totalUsersUsingCounter", $textColor);

// Output the image
header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>