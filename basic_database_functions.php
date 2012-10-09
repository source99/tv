<?php

function send_sql($db_conn, $sql_query){
    global $debug;
    $sql_return = $db_conn->query($sql_query);
       
    if($debug > 0) {
        if($sql_return){
            $sql_return = (string) $sql_return;
            print "$sql_query\nquery successful\nreturn = $sql_return\n\n";
        }    
        else {
            $sql_return = (string) $sql_return;
            print "$sql_query\nquery NOT successful\nreturn = $sql_return\n\n";
            exit -1;
        }
            
    }
    return sql_return;
    
}


function connect_tvdb(){

    $db_host = "127.0.0.1";
    $db_name = "matt_tvdb";
    $db_username = "root";
    $db_password = "root";

    // mysqli
    $mysqli_int = new mysqli($db_host, $db_username, $db_password, $db_name, 8889);
    if ($mysqli_int->connect_error) {
        die('Connect Error (' . $mysqli_int->connect_errno . ') '
                . $mysqli_int->connect_error);
    }
    //print_r($mysqli_int);
    print "database connected to successfully\n";
    return $mysqli_int;
   
    
}

function extract_series_variables($dom){
    global $series_id, $series_name, $status, $first_aired, $network, $network_id, $runtime, $genre, $actors, $overview, $airs_day_of_week, $airs_time;
    $series_id = $dom->Series->id;
    $series_name = make_not_null($dom->Series->SeriesName);
    $status = make_not_null($dom->Series->Status);
    $first_aired = make_not_null($dom->Series->FirstAired);
    $network = make_not_null($dom->Series->Network);
    $network_id = make_not_null($dom->Series->NetworkID);
    $runtime = make_not_null($dom->Series->Runtime); 
    $genre = make_not_null($dom->Series->Genre); 
    $actors = make_not_null($dom->Series->Actors); 
    $overview = make_not_null($dom->Series->Overview); 
    $airs_day_of_week = make_not_null($dom->Series->Airs_DayOfWeek); 
    $airs_time = make_not_null($dom->Series->Airs_Time); 
        
}

function insert_alter_series($db_conn, $series_id, $series_name, $status, $first_aired, $network, $network_id, $runtime, $genre, $actors, $overview, $airs_day_of_week, $airs_time, $imdb_id){
    print "\ninsert series - $series_id\n";
    
    
    $sql_query = "SELECT 1 FROM `tvseries` WHERE `id` = $series_id";
    $sql_return = $db_conn->query($sql_query);
    $size = $sql_return->num_rows;
   
    if($size > 0){
        //update
        $sql_query = "UPDATE `tvseries`
                      SET 
                    `id` = $series_id, 
                    `SeriesName` = $series_name, 
                    `Status` = $status, 
                    `FirstAired` = $first_aired, 
                    `Network` = $network, 
                    `NetworkID` = $network_id, 
                    `Runtime` = $runtime, 
                    `Genre` = $genre, 
                    `Actors` = $actors, 
                    `Overview` = $overview, 
                    `Airs_DayOfWeek` = $airs_day_of_week, 
                    `Airs_Time` = $airs_time
                      WHERE 
                      `id` = $series_id";
    }
    
    else {
        
    $sql_query = "INSERT INTO `tvseries` 
    (`id`, `SeriesName`, `Status`, `FirstAired`, `Network`, `NetworkID`, `Runtime`, 
     `Genre`, `Actors`, `Overview`, `Airs_DayOfWeek`, 
     `Airs_Time`) 
     VALUES (
    $series_id, $series_name, $status, $first_aired, $network, $network_id, 
    $runtime, $genre, $actors, $overview, $airs_day_of_week, 
    $airs_time)";
    }
    
    return send_sql($db_conn, $sql_query);
    
}

function extract_episode_variables($dom){
    global $episode_id, $series_id, $seasonid, $episode_number, $episode_name, $first_aired, $guest_stars, $director, $writer, $overview, $production_code, $season_number;
    
    $episode_id = $dom->id;
    $series_id = $dom->seriesid;
    $seasonid = $dom->seasonid;
    $episode_number = $dom->EpisodeNumber;
    $episode_name = make_not_null($dom->EpisodeName);
    $first_aired = make_not_null($dom->FirstAired);
    $guest_stars = make_not_null($dom->GuestStars);
    $director = make_not_null($dom->Director);
    $writer = make_not_null($dom->Writer);
    $overview = make_not_null($dom->Overview);
    $production_code = make_not_null($dom->ProductionCode);
    $season_number = $dom->SeasonNumber;
    
}


function check_insert_season($db_conn, $series_id, $season_number, $seasonid){
    $sql_query = "SELECT `id` FROM `tvseasons` WHERE `tvseasons`.`seriesid` = $series_id AND `tvseasons`.`season` = $season_number";
//    print "sql query = $sql_query\n";
    $return = $db_conn->query($sql_query);
    
    //print "return from select = $return\n";
    if($return->num_rows > 0){
        $row = mysqli_fetch_assoc($return);
        $id = $row['id'];
        //print "id = $id\n";
        return $id;
    }
    else
        //insert
        $sql_query = "INSERT INTO `tvseasons` (`id`, `seriesid`, `season`) VALUES ($seasonid, $series_id, $season_number)";
        $return = $db_conn->query($sql_query);
        
        //return new id
        $sql_query = "SELECT `id` FROM `tvseasons` WHERE `tvseasons`.`seriesid` = $series_id AND `tvseasons`.`season` = $season_number";
        $return = $db_conn->query($sql_query);
        $row = mysqli_fetch_assoc($return);
        $id = $row['id'];
//        print "id = $id\n";
        return $id;
    
}

function insert_episode($db_conn, $episode_id, $series_id, $seasonid, $episode_number, $episode_name, $first_aired, $guest_stars, $director, $writer, $overview, $production_code, $season_number){
    
    print "insert episode $series_id $episode_id\n";
    check_insert_season($db_conn, $series_id, $season_number, $seasonid);
    $sql_query = "INSERT INTO `tvepisodes`
            (`id`, `seasonid`, `EpisodeNumber`, `EpisodeName`, `FirstAired`,
             `GuestStars`, `Director`, `Writer`, `Overview`, `ProductionCode`,
             `SeasonNumber`, `seriesID`)
            VALUES (
             $episode_id, $seasonid, $episode_number, $episode_name, $first_aired, 
             $guest_stars, $director, $writer, $overview, $production_code, 
             $season_number, $series_id)";
    
 //   print "sql_query = $sql_query\n";
    
    
    //$return = $db_conn->query($sql_query);
    send_sql($db_conn, $sql_query);
}

?>
