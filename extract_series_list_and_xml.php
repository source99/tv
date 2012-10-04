<?php



$static_update_file = $argv[1];
if(!file_exists($static_update_file)){
    print "update file does not exist";
    exit -1;
}
else {
    print "opening static update file $static_update_file\n";
}

$dom = simplexml_load_file($static_update_file);
$count_ids = 0;
foreach ($dom->Series as $id):
    //print "id = $id->id\n";
    $get_path = "http://www.thetvdb.com/api/F6F68F91579731E2/series/$id->id/all/en.xml";
    $write_path = "series_xml/$id->id.xml";
//print "get path = $get_path\n";
//print "write path = $write_path\n";
file_put_contents($write_path, file_get_contents($get_path));
    $count_ids++;
    print "$count_ids\r";
endforeach;

print "total number of ids = $count_ids\n";

$contents = file_get_contents("http://www.thetvdb.com/api/F6F68F91579731E2/series/256160/all/en.xml");
//print_r($contents);

?>
