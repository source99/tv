<?php

function connect_tvdb(){

    $db_host = "localhost";
    $db_name = "tvdb";
    $db_username = "root";
    $db_password = "root";
    $search_string = $_GET['show'];

    // mysqli
    $mysqli_int = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($mysqli_int->connect_error) {
        die('Connect Error (' . $mysqli_int->connect_errno . ') '
                . $mysqli_int->connect_error);
    }
    print_r($mysqli_int);
    return $mysqli_int;
   
    
}

function update_series($series_id, $db_socket){
    

    //$series_id = 70327;
    $series_description_xml_url = "http://www.thetvdb.com/api/F6F68F91579731E2/series/$series_id/en.xml";
    $series_description_xml = simplexml_load_file($series_description_xml_url);
    $xml = $series_description_xml;
    $size = count($xml->Series);

    print "printing xml from within update_series function </br>";
    print_r($xml);
    print "</br>";
    
    $id = $xml->Series->id;
    print "id = $id</br>";
    $actors = $xml->Series->Actors;
    print "actors = $actors</br>";
    $Airs_DayOfWeek = (string) $xml->Series->Airs_DayOfWeek;
    print "Airs Day of week = $Airs_DayOfWeek</br>";
    $Airs_Time = (string) $xml->Series->Airs_Time;
    $ContentRating = $xml->Series->ContentRating;
    $FirstAired = $xml->Series->FirstAired;
    $Genre = $xml->Series->Genre;
    $IMDB_ID = $xml->Series->IMDB_ID;
    $Language = $xml->Series->Language;
    $Network = $xml->Series->Network;
    $NetworkID = $xml->Series->NetworkID;
    $Overview = $xml->Series->Overview;
    $Rating = $xml->Series->Rating;
    $RatingCount = $xml->Series->RatingCount;
    $Runtime = $xml->Series->Runtime;
    $SeriesID = $xml->Series->SeriesID;
    $SeriesName = $xml->Series->SeriesName;
    $Status = $xml->Series->Status;
    print "status = $Status</br>";
    $added = $xml->Series->added;
    $addedBy = $xml->Series->addedBy;
    $banner = $xml->Series->banner;
    $fanart = $xml->Series->fanart;
    $lastupdated = $xml->Series->lastupdated;
    $poster = $xml->Series->poster;
    $zap2it_id = $xml->Series->zap2it_id;

    
    if(!$Status)
        $Status = "\"\"";
    if(!$Network)
        $Network = "\"\"";
    if(!$Airs_DayOfWeek)
        $Airs_DayOfWeek = "\"\"";
    if(!$Airs_Time)
        $Airs_Time = "\"\"";
    
    
    
    $sz = count($Status);
    print "size of status is $sz";
    
    $sql_string = sprintf("SELECT 1 FROM `tvseries` WHERE `id`=%s","$series_id");
   
    print "<p>$sql_string</p>";

    $result = $db_socket->query($sql_string);
    
    print_r($result);
    
    print "does row exist? ";
    if($result->num_rows)
    {
        //update
        $sql_string = "UPDATE `tvseries` SET 
            `tvseries`.`Airs_DayOfWeek` = $Airs_DayOfWeek,
             `tvseries`.`Airs_Time` = $Airs_Time,
             `tvseries`.`Network` = \"$Network\",
             `tvseries`.`Status` = \"$Status\"
             WHERE `tvseries`.`id`=$id";

    }
    else {
        //insert
        $sql_string = "INSERT INTO `tvseries` 
        (`Airs_DayOfWeek`,`Airs_Time`,`Network`,`Status`, `id`)
        VALUES ($Airs_DayOfWeek, $Airs_Time, $Network, $Status, $series_id)";
    }
        print "status = $Status</br>";
    print "<p>sql_string = $sql_string </p>";
      
}

?>

<html>
    <head>
        <title>updating tvdb database</title>
    </head>
    <body>
        <? 
        $mysqli = new mysqli();
        $mysqli = connect_tvdb(); 
             //print_r($mysqli);
        print "hello</br>";
            print_r($mysqli);
            print "nothing";

        ?>
        
        <p>updating database for 70327</p>
       
        <? update_series(70327, $mysqli) ?>
        <? update_series(7032799999, $mysqli) ?>
   
       
        
        
        
    </body>
</html>