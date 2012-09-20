<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$show = $_GET["show"];
$tvdb_apikey = "F6F68F91579731E2";
$lang_url = "http://www.thetvdb.com/api/$tvdb_apikey/languages.xml";
//$languages = simplexml_load_file($lang_url);
$mirr_url = "http://www.thetvdb.com/api/$tvdb_apikey/mirrors.xml";
$mirr_xml = simplexml_load_file($mirr_url);
$mirr_typemask = $mirr_xml->Mirror->typemask;
$typemask_bit_0 = $mirr_typemask & 1; //xml type
$typemask_bit_1 = $mirr_typemask & 2; //banner type
$typemask_bit_2 = $mirr_typemask & 4; //zip type

//variables for mirrors for downloading of full tvdb.
//pull the mirror path into the variables
if($typemask_bit_0)
    $xml_mirrors = $mirr_xml->Mirror->mirrorpath; 
if($typemask_bit_1)
$banner_mirrors = $mirr_xml->Mirror->mirrorpath;
if($typemask_bit_2)
$zip_mirrors = $mirr_xml->Mirror->mirrorpath;

//get time from tvdb server
$time_url = "http://www.thetvdb.com/api/Updates.php?type=none";
$time_xml = simplexml_load_file($time_url);
//$previoustime = $time_xml->Time;
$previoustime = 1;

$series_list_url = "http://www.thetvdb.com/api/Updates.php?type=all&time=1";
$series_list_xml = simplexml_load_file($series_list_url);
$num_series = count($series_list_xml);
?>

<html>
    <head>
        <title>series premiere of <?= "$show" ?> </title>
    </head>
    <body>
        <h1>Series premiere of <?= "$show" ?> </h1>
        <p>number of series = <?= $num_series?> </p>
        


        
    </body>
</html>
