<?php
/**
 * Color Mapping Helper
 * This file contains the color name to hex code mapping
 * Include this file wherever you need to display colors
 */

function getColorMap() {
    return [
        'Black' => '#000000',
        'White' => '#FFFFFF',
        'Red' => '#DC143C',
        'Blue' => '#4169E1',
        'Green' => '#228B22',
        'Yellow' => '#FFD700',
        'Pink' => '#FF69B4',
        'Purple' => '#9370DB',
        'Gray' => '#808080',
        'Brown' => '#8B4513',
        'Navy' => '#000080',
        'Beige' => '#F5F5DC',
        'Orange' => '#FF8C00',
        'Maroon' => '#800000',
        'Cream' => '#FFFDD0',
        'Olive' => '#808000',
        'Khaki' => '#C3B091'
    ];
}

/**
 * Get hex code for a color name
 * Returns default gray if color not found
 */
function getColorHex($colorName) {
    $colorMap = getColorMap();
    $colorName = trim($colorName);
    return isset($colorMap[$colorName]) ? $colorMap[$colorName] : '#CCCCCC';
}
?>



