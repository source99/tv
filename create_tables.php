<?php

$debug = 1;

function send_sql($db_conn, $sql_query){
    global $debug;
    $sql_return = $db_conn->query($sql_query);
       
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

function drop_all($db_conn){
    $sql_string = "DROP TABLE `tvseasons`";
    send_sql($db_conn,$sql_string);
    $sql_string = "DROP TABLE `tvseries`";
    send_sql($db_conn,$sql_string);
    $sql_string = "DROP TABLE `tvepisodes`";
    send_sql($db_conn,$sql_string);
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

function create_tvseasons_table($db_conn){
    
    print "create tvseasons table\n";

    //print_r($db_conn);
    
$sql_query = "CREATE TABLE `tvseasons` (
`id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT,
`seriesid` INT( 10 ) UNSIGNED NOT NULL ,
`season` INT( 10 ) UNSIGNED NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = INNODB";    
    
    
send_sql($db_conn, $sql_query);

$sql_string = "ALTER TABLE  `tvseasons` ADD UNIQUE `uniqueseason`(
`seriesid` ,
`season`
)";    

send_sql($db_conn, $sql_string);

}

function create_tvseries_table($db_conn){
    
    print "create tvseries table";
    
    $sql_string = "CREATE TABLE  `tvseries` (
    `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
    `SeriesName` VARCHAR( 255 ) NOT NULL UNIQUE,
    `SeriesID` VARCHAR( 45 ) NULL DEFAULT NULL UNIQUE,
    `Status` VARCHAR( 100 ) NULL DEFAULT NULL ,
    `FirstAired` VARCHAR( 100 ) NULL DEFAULT NULL ,
    `Network` VARCHAR( 100 ) NULL DEFAULT NULL ,
    `NetworkID` INT( 10 ) NULL DEFAULT NULL ,
    `Runtime` VARCHAR( 100 ) NULL DEFAULT NULL ,
    `Genre` VARCHAR( 100 ) NULL DEFAULT NULL ,
    `Actors` TEXT NULL DEFAULT NULL ,
    `Overview` TEXT NULL DEFAULT NULL ,
    `Airs_DayOfWeek` VARCHAR( 45 ) NULL DEFAULT NULL ,
    `Airs_Time` VARCHAR( 45 ) NULL DEFAULT NULL ,
    PRIMARY KEY (  `id` )
    ) ENGINE = INNODB";

    send_sql($db_conn, $sql_string);

         
}    
    
function create_tvepisodes_table($db_conn){
    
    print "create tvepisodes table";
   
     $sql_string = "CREATE TABLE  `tvepisodes` (
    `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `seasonid` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
    `EpisodeNumber` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
    `EpisodeName` VARCHAR( 255 ) NULL DEFAULT  '',
    `FirstAired` VARCHAR( 45 ) NULL DEFAULT NULL ,
    `GuestStars` TEXT NULL DEFAULT NULL ,
    `Director` TEXT NULL DEFAULT NULL ,
    `Writer` TEXT NULL DEFAULT NULL ,
    `Overview` TEXT NULL DEFAULT NULL ,
    `ProductionCode` VARCHAR( 45 ) NULL DEFAULT NULL ,
    `SeasonNumber` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
    `seriesID` INT( 10 ) NOT NULL ,
    INDEX (`seasonid`),
    INDEX (`FirstAired`) ,
    INDEX (`seriesID` )
    ) ENGINE = INNODB";
    
     send_sql($db_conn, $sql_string);

}



//main
    print "creating tables\n";
    
    $db_conn = connect_tvdb();
    drop_all($db_conn);
    create_tvseasons_table($db_conn);
    create_tvseries_table($db_conn);
    create_tvepisodes_table($db_conn);
    
    $sql_string = "SHOW TABLES";
    $table_return = $db_conn->query($sql_string);
    print_r($table_return);
    if($table_return->num_rows == 3)
        print "3 tables created\n";
    else
        print "incorrect number of tables\n";

?>
