<?php
require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');



insertData($con, 'supplier',
    ['name', 'info'],
    [
        ['BeanSource Inc.', 'Espresso bean supplier'],
        ['DairyGold Supplies', 'Milk and cheese supplier'],
        ['Sunrise Bakery Co.', 'Bun and bread products'],
        ['TeaLeaf Traders', 'Tea bag and flavor syrup']
    ]
);
?>
