<?php
require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');


insertData($con, 'category', ['name'], [
    ['Drink'],
    ['Sandwich']
]);
?>
