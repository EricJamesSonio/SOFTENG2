<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = ''; 
$DATABASE = 'softeng';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if ($con) {
    echo "Database connected successfully";
} else {
    die("Connection failed: " . mysqli_connect_error());
}
?>
