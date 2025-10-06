<?php
$connection = mysqli_connect($_SERVER["DB_HOST"], $_SERVER["DB_USER"], $_SERVER["DB_PASSWORD"], $_SERVER["DB_NAME"],);

//$connection = mysqli_connect("localhost", "root", "admin", "projectbelinda");

mysqli_set_charset($connection, "utf8");

if (!$connection) {
    echo "Couldn't connect to DB";
    echo "ERROR: " .mysqli_connect_error();
    exit;
}
