<?PHP

/*
For each round,
rank its most active proofreaders in the last 1 - 7 - 14 days.
*/

$relPath= ( 0 ? 'pinc/' : '../c/pinc/' );
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'stages.inc');
include_once('page_events.inc');

theme('Currently active projects','header');

echo "<br /><br />";

$n_projects = 10;  // max projects to show for each round
$minutes = 15; // how many minutes count as current
$time_window = $minutes * 60;  // time window in seconds

$time_window_start = time() - $time_window;
$event_id = get_least_event_id_with_timestamp( $time_window_start );

// the first version of this did one query for each round, and
// took 100 seconds to build the page.
// so we'll just do one query, and then analyse the results.

$query = "
    SELECT DISTINCT
        page_events.projectid,
        projects.nameofwork,
        page_events.round_id
    FROM page_events, projects
    WHERE
        page_events.event_id >= $event_id
        AND page_events.event_type = 'saveAsDone'
        AND page_events.projectid = projects.projectid
";
$result = mysql_query( $query );

$projects = array();

while ( $row = mysql_fetch_row($result) ) 
{
    $projects[$row[2]][] = array($row[0], $row[1]);
}


foreach ( $Round_for_round_id_ as $round )
{
    echo "<h3>";
    echo _("Projects currently being worked on in ");
    echo $round->id;
    echo "</h3>\n";

    if ( count($projects[$round->id]) == 0 )
    {
        echo "None.";
    }
    else
    {
        echo "<ul>\n";
        foreach ( $projects[$round->id] as $count => $info ) 
        {
            if ( $count < $n_projects)
            {
                $projectid = $info[0];
                $project_name = $info[1];

                echo "<li>";
                echo "<a href='$code_url/project.php?id=$projectid'>";
                echo $project_name;
                echo "</a>";
                echo "</li>";
                echo "\n";
            }
        }
        echo "</ul>\n";
    }
}

echo "<br /><br />";


theme('','footer');

// vim: sw=4 ts=4 expandtab
?>
