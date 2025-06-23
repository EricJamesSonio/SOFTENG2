<?php

//Create table (Creating only onced, skip if exists)
//Insertdata (Only once insertion just for Pre define fields not for object)
function createtable($con, $name, $sql) {
    if (mysqli_query($con, $sql)) {
        echo "TABLE '$name' created successfully.<br>";
    } else {
        echo "Error creating table '$name': " . mysqli_error($con) . "<br>";
    }
}

function insertData($con, $table, $columns, $values) {
    $cols = implode(',', $columns);
    $placeholders = rtrim(str_repeat('?,', count($values[0])), ',');
    $checkCol = $columns[0]; // assume first column is unique (e.g., 'name')

    $inserted = 0;
    foreach ($values as $row) {
        // Initialize $count
        $count = 0;

        // Check if entry already exists
        $checkStmt = $con->prepare("SELECT COUNT(*) FROM $table WHERE $checkCol = ?");
        $checkStmt->bind_param('s', $row[0]);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        // Insert only if not exists
        if ($count == 0) {
            $stmt = $con->prepare("INSERT INTO $table ($cols) VALUES ($placeholders)");
            $stmt->bind_param(str_repeat('s', count($row)), ...$row);
            $stmt->execute();
            $stmt->close();
            $inserted++;
        }
    }

    echo "Inserted $inserted new rows into '$table'.<br>";
}

?>
