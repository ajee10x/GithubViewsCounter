<?php
/**
 * Theme Configuration for GitHubViewsCounter
 *
 * Provides RGB color settings based on the selected theme or custom values.
 */

function getThemeColors($theme, $bgColorHex = null, $textColorHex = null, $borderColorHex = null)
{
    // Default theme colors
    $themes = [
        'light' => [
            'bgColor' => [255, 255, 255], // White
            'textColor' => [0, 0, 0],     // Black
            'borderColor' => [0, 0, 0],   // Black
        ],
        'dark' => [
            'bgColor' => [34, 34, 34],    // Dark Gray
            'textColor' => [255, 255, 255], // White
            'borderColor' => [255, 255, 255], // White
        ],
    ];

    // Determine theme colors
    $colors = $themes[$theme] ?? $themes['light']; // Default to light if theme is invalid

    // If custom colors are provided, override the defaults
    if ($bgColorHex) {
        $colors['bgColor'] = hexToRgb($bgColorHex);
    }
    if ($textColorHex) {
        $colors['textColor'] = hexToRgb($textColorHex);
    }
    if ($borderColorHex) {
        $colors['borderColor'] = hexToRgb($borderColorHex);
    }

    return $colors;
}

/**
 * Convert HEX color code to RGB array
 */
function hexToRgb($hex)
{
    $hex = str_replace("#", "", $hex);
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    ];
}
