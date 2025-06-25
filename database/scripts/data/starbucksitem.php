<?php

require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');

// Fetch all Foreign keys!
$drinkId     = getIdByName($con, 'category', 'Drink');
$sandwichId  = getIdByName($con, 'category', 'Sandwich');

$espressoId  = getIdByName($con, 'subcategory', 'Espresso');
$teaId       = getIdByName($con, 'subcategory', 'Tea');
$fruityId    = getIdByName($con, 'subcategory', 'Fruity');
$eggId       = getIdByName($con, 'subcategory', 'Egg');
$baconId     = getIdByName($con, 'subcategory', 'Bacon');
$cheddarId   = getIdByName($con, 'subcategory', 'Cheddar');

insertData($con, 'starbucksitem', 
    ['name', 'price', 'quantity', 'category_id', 'subcategory_id', 'description'], [
    
    ['Iced Americano', 140.00, 20, $drinkId, $espressoId, 'A refreshing espresso drink served over ice'],
    ['Caffè Latte', 155.00, 15, $drinkId, $espressoId, 'Rich espresso with steamed milk and light foam'],
    ['Matcha Green Tea Latte', 165.50, 10, $drinkId, $teaId, 'Smooth and creamy matcha green tea with milk'],
    ['Very Berry Hibiscus Refresher', 170.00, 12, $drinkId, $fruityId, 'Fruity drink with real blackberries and green coffee extract'],
    ['Egg Sandwich', 130.00, 8, $sandwichId, $eggId, 'Classic breakfast sandwich with egg and cheese'],
    ['Bacon & Cheese Sandwich', 145.00, 5, $sandwichId, $baconId, 'Savory sandwich with crispy bacon and melted cheese'],
    ['Cheddar Melt Sandwich', 135.00, 7, $sandwichId, $cheddarId, 'Warm sandwich with rich cheddar cheese filling']
]);












?>