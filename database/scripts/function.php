<?php

//Create table (Creating only onced, skip if exists) Update changes such as ADDED COLLUMN AND REMOVED COLLUMN
//Insertdata (Only once insertion just for Pre define fields not for object)
function createTable($con, $name, $sql) {
    try {
        mysqli_query($con, $sql);
        echo "✅ TABLE '$name' created successfully.<br>";
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "⚠️ TABLE '$name' already exists. Checking for updates...<br>";

            // Step 1: Get current columns from DB
            $result = mysqli_query($con, "DESCRIBE `$name`");
            $existingColumns = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $existingColumns[] = $row['Field'];
            }

            // Step 2: Extract columns from the SQL definition
            $lines = explode("\n", $sql);
            $definedColumns = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (
                    $line === '' ||
                    stripos($line, 'PRIMARY KEY') !== false ||
                    stripos($line, 'FOREIGN KEY') !== false ||
                    stripos($line, 'CREATE TABLE') !== false ||
                    $line[0] === ')'
                ) {
                    continue;
                }

                $parts = preg_split('/\s+/', $line, 2);
                if (count($parts) < 2) continue;

                $column = trim($parts[0], '` ,');
                $definition = rtrim(trim($parts[1]), ',');
                $definedColumns[$column] = $definition;

                // Add missing columns
                if (!in_array($column, $existingColumns)) {
                    $alterSql = "ALTER TABLE `$name` ADD COLUMN `$column` $definition";
                    if (mysqli_query($con, $alterSql)) {
                        echo "✅ Added column '$column' to '$name'.<br>";
                    } else {
                        echo "❌ Failed to add column '$column': " . mysqli_error($con) . "<br>";
                    }
                }
            }

            // Step 3: Remove extra columns not in SQL
            foreach ($existingColumns as $existingColumn) {
                if (!array_key_exists($existingColumn, $definedColumns)) {
                    // Don't remove 'id' or foreign keys
                    if ($existingColumn === 'id') continue;

                    $alterSql = "ALTER TABLE `$name` DROP COLUMN `$existingColumn`";
                    if (mysqli_query($con, $alterSql)) {
                        echo "🗑️ Removed column '$existingColumn' from '$name'.<br>";
                    } else {
                        echo "❌ Failed to remove column '$existingColumn': " . mysqli_error($con) . "<br>";
                    }
                }
            }

        } else {
            echo "❌ Error creating table '$name': " . $e->getMessage() . "<br>";
        }
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
