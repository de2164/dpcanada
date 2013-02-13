<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once('calendar.inc');

// echo "<p>Special Days:</p>";

$now=getdate();
$today=$now['mday'];
$month=$now['mon'];
$year=$now['year'];
$day_name_length=2;

// get number of days in month, eg 28
$daysinmonth = date("t",mktime(0,0,0,$month,1,$year));

$res = mysql_query("
    SELECT
    open_day, close_day, close_month, color, display_name, info_url
    from special_days
    WHERE open_month = $month
    AND enable = 1
    ORDER BY open_day ASC;
");

// Days array: index is the day of month, values are arrays of two elements
// containing a url and a stylesheet class name
// We want to pre-load today with a style value, so the later code will have
// to allow for that by appending any special day style to the array element.

$days=array();
$days[$today]=array(NULL,"",NULL,NULL);

$localstyle = "<style type='text/css'>\n.calendar-month { font-size:130%; }\ntable.calendar td { text-align: right; padding:0; margin:0; }\ntable.calendar th { color: darkred; }\ntable.calendar { border: thin solid gray; background-color: #eeeeee; }\n";

// subtext is container for building table with special day keys, dates and links
$subtext="<table>\n";

// Walk through the days and assign special values to $days array
while( list($sd_open,$sd_close,$sd_close_month,$sd_color,$sd_name,$sd_url) = mysql_fetch_row($res) )
{
    // not-equal should handle case where dec begins and jan ends a whole-month special day
    if ( $sd_close_month != $month ) $sd_close = $daysinmonth+1;
    for ( $i = $sd_open; $i < $sd_close; $i++ ) {
	$days[$i]=array(NULL,$sd_color,NULL,$sd_name);
    }
    $localstyle .= ".$sd_color { font-style: italic; background-color: #" .$sd_color . "; }\n";

    // Put special-day link on key instead of day in calendar
    // This ensures each special day link will be accessible,
    // since only the latest would show if it were in the calendar.
    // If the event is multiple days, we should show the range
    // instead of just the opening date.
    $keydate = $sd_open;
    if ( ($sd_close - 1) > $sd_open) $keydate .= "&ndash;" . ($sd_close -1);
    $subtext .= "<tr><td style='text-align: center; background-color: #".$sd_color."; border: thin solid gray;'>". $keydate . "</td><td>";
    if ( $sd_url != "") {
	$subtext .= "<a style='font-size: 8pt;' href='" . $sd_url ."'>" . $sd_name . "</a>";
    }
    else {
	$subtext .= $sd_name;
    }
    $subtext .= "</td></tr>\n";
}

// Add a border and background to highlight today
$popday=$days[$today];
$popday[2]="<span style='border: thin solid black; background-color: white;'>".$today."</span>";
$days[$today]=$popday;

$localstyle .= "</style>\n";
$subtext .= "</table>\n";

echo $localstyle;

echo generate_calendar( $now['year'], $month, $days, $day_name_length, NULL, NULL);

echo $subtext;

// vim: sw=4 ts=4 expandtab
?>
