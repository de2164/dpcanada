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

foreach ( $Round_for_round_id_ as $round )
{
    echo "<h3>";
    echo _("Projects currently being worked on in ");
    echo $round->id;
    echo "</h3>\n";

    $query = "
        SELECT
            page_events.projectid,
            projects.nameofwork,
            COUNT(*) AS pages_saved, 
            MAX(page_events.timestamp) AS time_ago
        FROM page_events, projects
        WHERE
            page_events.event_id >= $event_id
            AND page_events.round_id='$round->id'
            AND page_events.event_type = 'saveAsDone'
            AND page_events.projectid = projects.projectid
        GROUP BY page_events.projectid
        ORDER BY time_ago ASC
        LIMIT $n_projects
    ";
	$result = mysql_query( $query );
    echo "<p>\n";

    $first = TRUE;
	while ( $info = mysql_fetch_row($result) )
	{
        $projectid = $info[0];
        $project_name = $info[1];
        $pages_saved = $info[2];

        echo "<li>";
        echo "<a href='$code_url/project.php?id=$projectid'>";
        echo $project_name;
        echo "</a>";
        echo " (";
        echo $pages_saved;
        if ($first) {
            echo " pages saved in the last $minutes minutes";
        }
        echo ")";
        echo "</li>";
        echo "\n";
        $first = FALSE;
    }
    echo "</p>\n";
}

echo "<br /><br />";


theme('','footer');

// vim: sw=4 ts=4 expandtab
?>
