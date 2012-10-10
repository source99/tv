<? php

function print_query($function_name, $query){
//input = function name as a string and query as a string
//output = 
        print "------------$function_name----------\n";
        print "sql_query = $query\n";
        print "---------------------------------\n";
}

function exact_match($search_term){
//takes string as an input and searches DB for an exact match
//return the series_id integer
//returns -1 if not found
//assumes $db_conn conntect is already connected to database
global $db_conn, $debug;

$sql_query = 	"SELECT `tvseries`.`id` 
              	FROM `tvseries`
		WHERE
		`tvseries`.`SeriesName` = $search_term";

if($debug > 3){
	print_query("exact_match", $sql_query);

}
 



}

function like_match($search_term){
//takes string as an input and returns a 2 D array with each line containing
//series name and series ID
//returns empty array if not found 
//assumes $db_conn conntect is already connected to database
global $db_conn;

}

function series_ended($series_id){
//takes series id integer as an input and returns of the series is ended
//returns TRUE for ended 
//returns FALSE for series still airing
//assumes $db_conn conntect is already connected to database
global $db_conn;

}

function in_active_season($series_id){
//input = series_id integer
//returns TRUE if season is currently active
//returns FALSE if season is not currently active
//assumes $db_conn conntect is already connected to database
global $db_conn;

}

function current_season($series_id){
//input = series_id integer
//returns the number of the current season.
//this is not the season ID number but just the current number of the season.  
//assumes $db_conn conntect is already connected to database
global $db_conn;

}

function upcoming_season($series_id){
//input = series_id integer
//returns the number of the next upcoming season
//this will return the next season even if we are currently in an active season.
//this is not the season ID number
//assumes $db_conn conntect is already connected to database
global $db_conn;

}

function first_episode($series_id, $season){
//input = series id and season number
//returns the episode id integer of the season premier of that episode
//assumes $db_conn conntect is already connected to database
global $db_conn;

}


function date_and_time_of_episode($episode_id){
//input = episode id integer
//returns the date and time of the episode
//assumes $db_conn conntect is already connected to database
global $db_conn;

}


function current_date(){
//returns the current date in string format

}

function date_1_is_earlier($date1, $date2){
//takes 2 dates in string format as input
//returns TRUE if $date_1 is earlier than $date_2


}








?>
