<?php

require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');

// Map: item name => list of [ingredient name, quantity value, quantity unit]
$map = [
    'Iced Americano' => [
        ['Espresso Shot', 60, 'ml'],
    ],
    'Caffè Latte' => [
        ['Espresso Shot', 30, 'ml'],
        ['Steamed Milk', 150, 'ml'],
    ],
    'Matcha Green Tea Latte' => [
        ['Steamed Milk', 150, 'ml'],
        ['Tea Bag', 1, 'piece'],
    ],
    'Very Berry Hibiscus Refresher' => [
        ['Fruit Syrup', 100, 'ml'],
        ['Tea Bag', 1, 'piece'],
    ],
    'Egg Sandwich' => [
        ['Egg Patty', 1, 'piece'],
        ['Cheddar Cheese', 30, 'g'],
        ['Croissant Bun', 1, 'piece'],
    ],
    'Bacon & Cheese Sandwich' => [
        ['Bacon Strips', 2, 'slice'],
        ['Cheddar Cheese', 30, 'g'],
        ['Croissant Bun', 1, 'piece'],
    ],
    'Cheddar Melt Sandwich' => [
        ['Cheddar Cheese', 60, 'g'],
        ['Croissant Bun', 1, 'piece'],
    ],
    'Ice Starbucks Purple Cream' => [
        ['Espresso Shot', 30, 'ml'],
        ['Steamed Milk', 150, 'ml'],
    ],
];

$values = [];

foreach ($map as $itemName => $ingredients) {
    $itemId = getIdByName($con, 'starbucksitem', $itemName);
    if (!$itemId) continue;

    foreach ($ingredients as [$ingredientName, $qtyValue, $unit]) {
        $ingredientId = getIdByName($con, 'ingredient', $ingredientName);
        if (!$ingredientId) continue;

        $values[] = [$itemId, $ingredientId, $qtyValue, $unit];
    }
}

insertData(
    $con,
    'item_ingredient',
    ['item_id', 'ingredient_id', 'quantity_value', 'quantity_unit'],
    $values,
    ['item_id', 'ingredient_id'] // prevent duplicates
);

?>
