<?php
header("Content-Type: image/png");

// GitHub's link color
$textColorHex = '0969da'; // GitHub blue for links

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

$textColorRgb = hexToRgb($textColorHex);

// Text to display
$text = "Made With GitHubViewsCounter";

// Font settings
$fontPaths = [
    __DIR__ . '/../fonts/SegoeUI.ttf',    // Segoe UI
    __DIR__ . '/../fonts/Roboto-Regular.ttf', // Roboto
    __DIR__ . '/../fonts/HelveticaNeue.ttf',  // Helvetica Neue
    __DIR__ . '/../fonts/Arial.ttf'      // Arial (fallback)
];
$fontPath = null;
foreach ($fontPaths as $path) {
    if (file_exists($path)) {
        $fontPath = $path;
        break;
    }
}

if (!$fontPath) {
    die("No valid font file found.");
}

$fontSize = 10; // Font size
$underlineOffset = 2; // Distance between text and underline

// Calculate the text box size
$bbox = imagettfbbox($fontSize, 0, $fontPath, $text);
$textWidth = abs($bbox[2] - $bbox[0]);
$textHeight = abs($bbox[7] - $bbox[1]);

// Create the image
$width = $textWidth;
$height = $textHeight + $underlineOffset + 2; // Add space for underline
$image = imagecreatetruecolor($width, $height);
imagesavealpha($image, true);
$transparentColor = imagecolorallocatealpha($image, 0, 0, 0, 127);
imagefill($image, 0, 0, $transparentColor);

// Allocate text color
$textColor = imagecolorallocate($image, $textColorRgb[0], $textColorRgb[1], $textColorRgb[2]);

// Add text to the image
imagettftext($image, $fontSize, 0, 0, $textHeight, $textColor, $fontPath, $text);

// Add underline
$underlineY = $textHeight + $underlineOffset;
imageline($image, 0, $underlineY, $textWidth, $underlineY, $textColor);

// Output the image
imagepng($image);
imagedestroy($image);
