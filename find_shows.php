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


//$sql_query = sprintf("SELECT `SeriesName`, `id` FROM `tvseries` WHERE `SeriesName` like '%%%s%%'",$search_string);
//$sql_join_query = sprintf("SELECT `SeriesName.id`, `tvseasons.season`, WHERE `SeriesName` like '%%%s%%', Seriesname");

$sql_query = sprintf("SELECT  `tvepisodes`.`firstaired`, `tvseries`.`SeriesName` ,  `tvseasons`.`season` ,  `tvepisodes`.`episodenumber` ,  `tvepisodes`.`Overview` 
FROM  `tvseries` ,  `tvseasons` ,  `tvepisodes` 
WHERE  `tvseries`.`id` =  `tvseasons`.`seriesid` 
AND  `tvseasons`.`id` =  `tvepisodes`.`seasonid` 
AND  `tvepisodes`.`EpisodeNumber` = 1 
AND  `tvseries`.`SeriesName` LIKE  '%%%s%%'
ORDER BY `tvepisodes`.`firstaired` DESC
LIMIT 0 , 3", $search_string);



$result = $mysqli->query($sql_query);


?>


<html>
    <head>
        <title>looking for a show in MySQL DB</title>
    </head>
    <body>
        <p>Looking for the string "<?= $search_string ?>" in tv database </p>
        
        <p>SQL query = <?= $sql_query ?></p>
        <p>upcoming episodes </p>
        <p>
            <?  
                while ($row = $result->fetch_assoc()) {
                    echo $row['SeriesName'];
                    print " ";
                    echo $row['firstaired'];
                    print " - season ";
                    echo $row['season'];
                    print " episode ";
                    echo $row['episodenumber'];
                    echo "</br>overview - ";
                    echo $row['Overview'];
                    print "</br></br>";
                    //echo $row['SeriesName'] $row['firstaired'];
                    }
            ?>
        </p>
        
        
    </body>
</html>