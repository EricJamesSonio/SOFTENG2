<?php

require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');

// Ingredients with supplier mappings
$ingredients = [
    ['Espresso Shot', 5000, 'ml', 'BeanSource Inc.'],
    ['Steamed Milk', 10000, 'ml', 'DairyGold Supplies'],
    ['Tea Bag', 100, 'piece', 'TeaLeaf Traders'],
    ['Fruit Syrup', 2000, 'ml', 'TeaLeaf Traders'],
    ['Egg Patty', 50, 'piece', 'DairyGold Supplies'],
    ['Bacon Strips', 100, 'slice', 'DairyGold Supplies'],
    ['Cheddar Cheese', 5000, 'g', 'DairyGold Supplies'],
    ['Croissant Bun', 50, 'piece', 'Sunrise Bakery Co.'],
];

$values = [];

foreach ($ingredients as [$name, $qty, $unit, $supplierName]) {
    $supplierId = getIdByName($con, 'supplier', $supplierName);
    if (!$supplierId) {
        echo "⚠️ Supplier '$supplierName' not found for ingredient '$name'. Skipping.\n";
        continue;
    }

    $values[] = [$name, $qty, $unit, $supplierId];
}

insertData(
    $con,
    'ingredient',
    ['name', 'quantity_in_stock', 'stock_unit', 'supplier_id'],
    $values,
    ['name'] // Prevent duplicates
);
