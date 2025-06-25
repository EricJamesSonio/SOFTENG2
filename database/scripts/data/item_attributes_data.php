<?php

require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');

// Step 1: Get all item IDs from starbucksitem
$itemIds = [];
$result = mysqli_query($con, "SELECT id FROM starbucksitem");
while ($row = mysqli_fetch_assoc($result)) {
    $itemIds[] = $row['id'];
}

// Step 2: Define default attributes for each item
$defaultAttributes = ['Caffeine Level', 'Sweetness', 'Tea Level'];
$defaultValue = 'Medium';

$attributeRows = [];

foreach ($itemIds as $itemId) {
    foreach ($defaultAttributes as $attrName) {
        $attributeRows[] = [$itemId, $attrName, $defaultValue];
    }
}

// Step 3: Insert into item_attributes
insertData($con, 'item_attributes', ['item_id', 'attribute_name', 'attribute_value'], $attributeRows);

?>
