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

    //print "printing xml from within update_series function </br>";
    //print_r($xml);
    //print "</br>";
    //$ContentRating = $xml->Series->ContentRating;  //removed because I dont have a key to correlate int to PG-13, etc
   
    $id = $xml->Series->id;
    $Actors = (string) $xml->Series->Actors;
    $Airs_DayOfWeek = (string) $xml->Series->Airs_DayOfWeek;
    $Airs_Time = (string) $xml->Series->Airs_Time;
    $FirstAired = (string) $xml->Series->FirstAired;
    $Genre = (string)$xml->Series->Genre;
    $IMDB_ID = (string) $xml->Series->IMDB_ID;
    $Language = (string)$xml->Series->Language;  
    $Network = (string)$xml->Series->Network;
    $NetworkID = $xml->Series->NetworkID;
    $Overview = (string) $xml->Series->Overview;
    $Runtime = (string) $xml->Series->Runtime;
    $SeriesID = $xml->Series->SeriesID;
    $SeriesName = (string) $xml->Series->SeriesName;
    $Status = (string)$xml->Series->Status;
    $added = $xml->Series->added;
    $lastupdated = $xml->Series->lastupdated;

    print "SeriesName = $SeriesName</br>";  
    
    $sql_string = sprintf("SELECT 1 FROM `tvseries` WHERE `id`=%s","$series_id");
    $result = $db_socket->query($sql_string);
    
    if($result->num_rows)
    {
         //update
        $sql_string = "UPDATE `tvseries` SET 
             `tvseries`.`actors`=\"$Actors\",
             `tvseries`.`Airs_DayOfWeek` = \"$Airs_DayOfWeek\",
             `tvseries`.`FirstAired` = \"$FirstAired\",
             `tvseries`.`Genre` = \"$Genre\",
             `tvseries`.`IMDB_ID` = \"$IMDB_ID\",
             `tvseries`.`Network` = \"$Network\",
             `tvseries`.`Overview` = \"$Overview\",
             `tvseries`.`Runtime` = \"$Runtime\",
             `tvseries`.`SeriesID` = $SeriesID,
             `tvseries`.`SeriesName` = \"$SeriesName\",
             `tvseries`.`added` = \"$added\",
             `tvseries`.`lastupdated` = $lastupdated,
             `tvseries`.`Airs_Time` = \"$Airs_Time\",
             `tvseries`.`Status` = \"$Status\"
             WHERE `tvseries`.`id`=$id";

    }
    else {
        //insert
        $sql_string = "INSERT INTO `tvseries` 
        
        (
             `id`,
             `actors`,
             `Airs_DayOfWeek`,
             `FirstAired`,
             `Genre`,
             `IMDB_ID`,
             `Network`,
             `Overview`,
             `Runtime`,
             `SeriesID`,
             `SeriesName`,
             `added`,
             `lastupdated`,
             `Airs_Time`,
             `Status`)
        VALUES 
        (
             $id,
             \"$Actors\",
             \"$Airs_DayOfWeek\",
             \"$FirstAired\",
             \"$Genre\",
             \"$IMDB_ID\",
             \"$Network\",
             \"$Overview\",
             \"$Runtime\",
             $SeriesID,
             \"$SeriesName\",
             \"$added\",
             $lastupdated,
             \"$Airs_Time\",
             \"$Status\"
        )";
        

        
    }
    
    
    //print "<p>sql_string = $sql_string </p>";
       $result = $db_socket->query($sql_string);
       if(!result)
           print "ERROR sql string = </br> $sql_string</br>";
       return result;
}


function update_episode($episode_id, $db_socket){
    

    //$series_id = 70327;
    $episode_description_xml_url = "http://www.thetvdb.com/api/F6F68F91579731E2/episodes/$episode_id/en.xml";
    $episode_description_xml = simplexml_load_file($episode_description_xml_url);
    $xml = $episode_description_xml;
    $size = count($xml->Episode);

    print "printing xml from within update_series function </br>";
    print_r($xml);
    //print "</br>";
    //$ContentRating = $xml->Series->ContentRating;  //removed because I dont have a key to correlate int to PG-13, etc
   
    

    
    $id = $xml->Episode->id;
    $seasonid = $xml->Episode->seasonid;
    $EpisodeNumber =  $xml->Episode->EpisodeNumber;
    $EpisodeName = (string) $xml->Episode->EpisodeName;
    $FirstAired = (string) $xml->Episode->FirstAired;
    $GuestStars = (string)$xml->Episode->GuestStars;
    $Director = (string) $xml->Episode->Director;
    $Writer = (string)$xml->Episode->Writer;  
    $Overview = (string)$xml->Episode->Overview;
    $ProductionCode = $xml->Episode->ProductionCode;
    $lastupdated = (string) $xml->Episode->lastupdated;
    $flagged = (string) $xml->Episode->flagged;
    $seriesid = $xml->Episode->seriesid;
    $IMDB_ID = (string) $xml->Episode->IMDB_ID;
    $SeasonNumber = (string)$xml->Episode->SeasonNumber;
    $Language = $xml->Episode->Language;

    print "Seriesid = $seriesid</br>";  
    //check if episode exists
    $sql_string = sprintf("SELECT 1 FROM `tvepisodes` WHERE `id`=%s","$episode_id");
    $result = $db_socket->query($sql_string);
    
                //if episode does not exist need to check if season exists.  
    
    $season_check_string = sprintf("SELECT 1 FROM `tvseaons` WHERE `id`=%s", $seasonid);
    print "Season check string = $season_check_string";
    $season_check = $db_socket->query($season_check_string);
    
    if(!$season_check){
        $create_season_string = "INSERT INTO `tvseasons`
               (
                `tvseasons`.`seriesid`,
                `tvseasons`.`season`
               )
            VALUES
               (
                $seriesid,
                $SeasonNumber
               )";
        $result_create_season = $db_socket->query($create_season_string);
        print "season create string = $result_create_season";
            
    }
    
    
    if(!$IMDB_ID)
        $IMDB_ID = "NULL";
    
    if($result->num_rows)
    {
         //update
        $sql_string = "UPDATE `tvepisodes` SET 
        `tvepisodes`.`seasonid` =   $seasonid,
        `tvepisodes`.`EpisodeNumber` =  $EpisodeNumber,
        `tvepisodes`.`EpisodeName` =  \"$EpisodeName\",
        `tvepisodes`.`FirstAired` =  \"$FirstAired\",
        `tvepisodes`.`GuestStars` =   \"$GuestStars\",
        `tvepisodes`.`Director` =  \"$Director\",
        `tvepisodes`.`Writer` =  \"$Writer\",
        `tvepisodes`.`Overview` =  \"$Overview\",
        `tvepisodes`.`ProductionCode` =  \"$ProductionCode\",
        `tvepisodes`.`lastupdated` =  \"$lastupdated\",
        `tvepisodes`.`flagged` =   \"$flagged\",
        `tvepisodes`.`seriesid` =  $seriesid,
        `tvepisodes`.`IMDB_ID` =  $IMDB_ID
         WHERE `tvepisodes`.`id`=$id";
    } 
     else {
        //insert
    $sql_string = "INSERT INTO `tvdb`.`tvepisodes` 
        (
        `id`, 
        `seasonid`, 
        `EpisodeNumber`, 
        `EpisodeName`, 
        `FirstAired`, 
        `GuestStars`, 
        `Director`, 
        `Writer`, 
        `Overview`, 
        `ProductionCode`, 
        `lastupdated`, 
        `flagged`, 
        `seriesid`, 
        `IMDB_ID`
        ) VALUES 
        (
          $id,
          $seasonid,
          $EpisodeNumber,
          \"$EpisodeName\",
          \"$FirstAired\",
          \"$GuestStars\",
          \"$Director\",
          \"$Writer\",
          \"$Overview\",
          \"$ProductionCode\",
          \"$lastupdated\",
          \"$flagged\",
          $seriesid,
          $IMDB_ID
        )";
         
    }
    
    
    print "<p>sql_string = $sql_string </p>";
       $result = $db_socket->query($sql_string);
       if(!result)
           print "ERROR sql string = </br> $sql_string</br>";
       return result;
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
        //print "hello</br>";
        //print_r($mysqli);
        //print "nothing";

        ?>
        <? 
        $curr_id = 70327;
        
        ?>
        <?
        
        $req_time = 1339804800;
        $loop_count = 0;
        //while ($req_time < 1348636153) {
            //$series_list_xml_url = "http://www.thetvdb.com/api/Updates.php?type=all&time=$req_time";
            $series_list_xml_url = "http://localhost/tv/updates_month.xml";
            
            
            print "ASDFURL - $series_list_xml_url</br>";
            $series_list_xml = simplexml_load_file($series_list_xml_url);
            
            
            foreach($series_list_xml->Series as $curr_id):
                print "<p>series id = $curr_id->id</p>";
                $result = update_series($curr_id->id, $mysqli);
            endforeach;
            
            foreach($series_list_xml->Episode as $curr_id):
                print "<p>episode id = $curr_id->id</p>";
                $result = update_episode($curr_id->id, $mysqli);
            endforeach;            
            /*
            foreach($series_list_xml->Episode as $curr_id):
                print "<p>updating database for $curr_id loop count = $loop_count </p>";
                $result = update_episode($curr_id, $mysqli);
            
                $loop_count++;
                if(!result)
                break;
            
                endforeach;
           */ 
            $req_time = $req_time + (60*60*24);
        // }  //while loop
        ?>
        
        
        
        <p>finished</p>
        
        
        
    </body>
</html>