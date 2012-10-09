<?php

/*
 * download weekly file
 * download series files into empty directory
 * run insert / alter
 * 
 */
require 'basic_database_functions.php';


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

function download_weekly_into_dom(){
     $return = simplexml_load_file("http://www.thetvdb.com/api/F6F68F91579731E2/updates/updates_week.xml");
     if(empty($return)){
         print "unable to download weekly update\n";
         exit -1;
     }
     return $return;
 }

 function download_xml_files($weekly_dom){
     $dom = $weekly_dom;
     foreach ($dom->Series as $id):
    //print "id = $id->id\n";
    $get_path = "http://www.thetvdb.com/api/F6F68F91579731E2/series/$id->id/all/en.xml";
    $write_path = "weekly_updates/$id->id.xml";
    //print "get path = $get_path\n";
    //print "write path = $write_path\n";
    file_put_contents($write_path, file_get_contents($get_path));
    $count_ids++;
    print "$count_ids\r";
    endforeach;    
 }
 
 
 
 
 function process_xml_files($db_conn){
     
    if ($handle = opendir('weekly_updates/')) {
        echo "Directory handle: $handle\n";
        echo "Entries:\n";
        $entry = readdir($handle);
        $entry = readdir($handle);
        while (false !== ($entry = readdir($handle))) {
                echo "$entry\n";
         $series_dom = simplexml_load_file($entry);
         if(empty($series_dom))
             goto next_series;
         extract_series_variables($series_dom);
         insert_alter_series($db_conn, $series_id, $series_name, $status, $first_aired, $network, $network_id, $runtime, $genre, $actors, $overview, $airs_day_of_week, $airs_time);
         foreach($dom->Episode as $episode):
    //   print "-----------------inserting episode------------------";
         extract_episode_variables($episode);
         insert_episode($db_conn, $episode_id, $series_id, $seasonid, $episode_number, $episode_name, $first_aired, $guest_stars, $director, $writer, $overview, $production_code, $season_number);
    
         
         endforeach;


         next_series:       
             print "";
        }
    }
 }
 
 
 //main
 $db_conn = connect_tvdb();
 $dom = download_weekly_into_dom();
 //download_xml_files($db_conn);
 process_xml_files();

?>
