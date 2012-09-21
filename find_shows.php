<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$db_host = "localhost";
$db_name = "tvdb";
$db_username = "root";
$db_password = "root";
$search_string = $_GET['show'];

// mysqli
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}


$sql_query = sprintf("SELECT `SeriesName` FROM `tvseries` WHERE `SeriesName` like '%%%s%%'",$search_string);
$result = $mysqli->query($sql_query);


?>


<html>
    <head>
        <title>looking for a show in MySQL DB</title>
    </head>
    <body>
        <p>setup:</p>
        <p>database address = <?=$db_host ?> </p>
        <p>database username = <?=$db_username ?></p>
        <p>database password = <?= $db_password ?></p>
        <p>database = <?= $db_name ?></p>
        <p>Looking for the string "<?= $search_string ?>" in tv database </p>
        
        <? echo  'Successfully logged into db... ' . $mysqli->host_info . "\n" ?>;
        
        <p>SQL query = <?= $sql_query ?></p>
        <p>size of query return is <?= $result->num_rows ?></p>
        <p>return of query is </p>
        <p>
            <?  
                while ($row = $result->fetch_assoc()) {
                    echo $row['SeriesName'] . "<br>";
                    }
            ?>
        </p>
        
        
    </body>
</html>