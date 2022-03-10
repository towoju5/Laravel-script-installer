<?php

function db_query($sql){
    $servername = $_REQUEST['db_host'];
    $username = $_REQUEST['db_user'];
    $password = $_REQUEST['db_pass'];
    $dbname = $_REQUEST['db_name'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    $execute = $conn->query($sql);
    if (!$execute) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $execute;
}


?>
