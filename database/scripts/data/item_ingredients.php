<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../function.php';

$map = [
    'Iced Americano' => [
        ['Espresso Shot', 60],
    ],
    'Caffè Latte' => [
        ['Espresso Shot', 30],
        ['Steamed Milk', 150],
    ],
    'Matcha Green Tea Latte' => [
        ['Steamed Milk', 150],
        ['Tea Bag', 1],
    ],
    'Very Berry Hibiscus Refresher' => [
        ['Fruit Syrup', 100],
        ['Tea Bag', 1],
    ],
    'Egg Sandwich' => [
        ['Egg Patty', 1],
        ['Cheddar Cheese', 30],
        ['Croissant Bun', 1],
    ],
    'Bacon & Cheese Sandwich' => [
        ['Bacon Strips', 2],
        ['Cheddar Cheese', 30],
        ['Croissant Bun', 1],
    ],
    'Cheddar Melt Sandwich' => [
        ['Cheddar Cheese', 60],
        ['Croissant Bun', 1],
    ],
];

$values = [];

foreach ($map as $itemName => $ingredients) {
    $itemId = getIdByName($con, 'starbucksitem', $itemName);
    if (!$itemId) continue;

    foreach ($ingredients as [$ingredientName, $qty]) {
        $ingredientId = getIdByName($con, 'ingredient', $ingredientName);
        if (!$ingredientId) continue;

        $values[] = [$itemId, $ingredientId, $qty];
    }
}

insertData($con, 'item_ingredient', ['item_id', 'ingredient_id', 'quantity'], $values);
?>
