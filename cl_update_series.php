<?php

//declare global variables
$debug = 0;

$series_id = 0;
$series_name = "series name";
$status = "status";
$first_aired = "first aired";
$network = "network";
$network_id = 0;
$runtime = 0;
$genre = "genre"; 
$actors = "actors";
$overview = "overview";
$airs_day_of_week = "airs day of week";
$airs_time = "airs time";


function send_sql($db_conn, $sql_query){
    global $debug;
    $sql_return = $db_conn->query($sql_query);
    printf("asdfSelect returned %d rows.\n", $sql_return->num_rows);   
    if($debug > 0) {
        if($sql_return){
            print "$sql_query\nquery successful\nreturn = $sql_return\n\n";
        }    
        else {
            print "$sql_query\nquery NOT successful\nreturn = $sql_return\n\n";
        
        }
            
    }
    return sql_return;
    
}

function is_xml_empty_field($xml){
    if($xml == "")
        return TRUE;
    else
        return FALSE;
}


function reset_series_variables(){
    global $series_id, $series_name, $status, $first_aired, $network, $network_id, $runtime, $genre, $actors, $overview, $airs_day_of_week, $airs_time;
    $series_id = 0;
    $series_name = "series name";
    $status = "status";
    $first_aired = "first aired";
    $network = "network";
    $network_id = 0;
    $runtime = 0;
    $genre = "genre"; 
    $actors = "actors";
    $overview = "overview";
    $airs_day_of_week = "airs day of week";
    $airs_time = "airs time";
}

function reset_episode_variables(){
    global $series_id, $seasonid, $episode_number, $episode_name, $first_aired, $guest_stars, $director, $writer, $overview, $production_code, $season_number;
    $episode_id = 0;
    $series_id = 0;
    $seasonid = 0;
    $episode_number = 0;
    $episode_name = "episode name";
    $first_aired = "first aired";
    $guest_stars = "guest stars";
    $director = "director";
    $writer = "writer";
    $overview = "overview";
    $production_code = "production code";
    $season_number = 0;

    
}

function reset_season_variables(){
    global $series_id, $season;
    $series_id = 0;
    $season = 0;
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
    print "database connected to successfully\n";
    return $mysqli_int;
   
    
}


function load_file_into_xml($filename){
    $xml_filename = "series_xml/$filename";
    $dom = simplexml_load_file($xml_filename);
    return $dom;
}

function make_not_null($xml){
    if(is_xml_empty_field($xml))
        $return_val = "\"\"";
    else
        $return_val = "\"$xml\"";
    return $return_val;
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
    
 if($debug > 2){   
    print "1*******Printing extract episode variables\n";
    print "2*******$episode_id\n";
    print "3*******$series_id\n";
    print "4*******$seasonid\n";
    print "5*******$episode_number\n";
    print "6*******$episode_name\n";
    print "7*******$first_aired\n";
    print "8*******$guest_stars\n";
    print "9*******$director\n";
    print "a*******$writer\n";
    print "b*******$overview\n";
    print "c*******$production_code\n";
    print "d*******$season_number\n";
 }
}

function check_insert_season($db_conn, $series_id, $season_number, $seasonid){
    $sql_query = "SELECT `id` FROM `tvseasons` WHERE `tvseasons`.`seriesid` = $series_id AND `tvseasons`.`season` = $season_number";
    print "sql query = $sql_query\n";
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
        print "id = $id\n";
        return $id;
    
}

function insert_episode($db_conn, $episode_id, $series_id, $seasonid, $episode_number, $episode_name, $first_aired, $guest_stars, $director, $writer, $overview, $production_code, $season_number){
    
    print "insert episode\n";
    check_insert_season($db_conn, $series_id, $season_number, $seasonid);
    $sql_query = "INSERT INTO `tvepisodes`
            (`id`, `seasonid`, `EpisodeNumber`, `EpisodeName`, `FirstAired`,
             `GuestStars`, `Director`, `Writer`, `Overview`, `ProductionCode`,
             `SeasonNumber`, `seriesID`)
            VALUES (
             $episode_id, $seasonid, $episode_number, $episode_name, $first_aired, 
             $guest_stars, $director, $writer, $overview, $production_code, 
             $season_number, $series_id)";
    
    print "sql_query = $sql_query\n";
    
    
    $return = $db_conn->query($sql_query);
}


function insert_series($db_conn, $series_id, $series_name, $status, $first_aired, $network, $network_id, $runtime, $genre, $actors, $overview, $airs_day_of_week, $airs_time, $imdb_id){
    print "\ninsert series - $series_id\n";
    $sql_query = "INSERT INTO `tvseries` 
    (`id`, `SeriesName`, `Status`, `FirstAired`, `Network`, `NetworkID`, `Runtime`, 
     `Genre`, `Actors`, `Overview`, `Airs_DayOfWeek`, 
     `Airs_Time`) 
     VALUES (
    $series_id, $series_name, $status, $first_aired, $network, $network_id, 
    $runtime, $genre, $actors, $overview, $airs_day_of_week, 
    $airs_time)";
    
    send_sql($db_conn, $sql_query);
    
}

//main start
print "main start\n";
$debug = 0;
$db_conn = connect_tvdb();

$ret = 
print "returns $ret\n";

$id_number = "70327.xml";
$dom = load_file_into_xml($id_number);
reset_series_variables();
extract_series_variables($dom);
insert_series($db_conn, $series_id, $series_name, $status, $first_aired, $network, $network_id, $runtime, $genre, $actors, $overview, $airs_day_of_week, $airs_time, $imdb_id);
foreach($dom->Episode as $episode):
    print "-----------------inserting episode------------------";
    reset_episode_variables();
    reset_season_variables();
    extract_episode_variables($episode);
    insert_episode($db_conn, $episode_id, $series_id, $seasonid, $episode_number, $episode_name, $first_aired, $guest_stars, $director, $writer, $overview, $production_code, $season_number);
endforeach;



?>
