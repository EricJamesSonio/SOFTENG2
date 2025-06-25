<?php

require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');

// Insert some default ingredients (you can expand this later)
insertData($con, 'ingredients', ['name'], [
    ['Espresso Shot'],
    ['Steamed Milk'],
    ['Tea Bag'],
    ['Fruit Syrup'],
    ['Egg Patty'],
    ['Bacon Strips'],
    ['Cheddar Cheese'],
    ['Croissant Bun'],
]);

?>
