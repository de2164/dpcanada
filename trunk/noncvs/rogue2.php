<?PHP
$relPath = '../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once('page_events.inc');

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) exit();

$max_days_back = 7;

$username = @$_GET['username'];
if ( empty($username) )
{
    echo "<form method='get'>";
    echo "username: ";
    echo "<input type='text' size='20' name='username'>";
    echo "<br>\n";
    echo "days back: ";
    echo "<input type='text' size='5' name='days_back'>";
    echo " (max = $max_days_back)";
    echo "<br>\n";
    echo "<input type='submit' value='Search for saved pages'>";
    echo "</form>";
    exit;
}

$days_back = @$_GET['days_back'];

if ( $days_back > $max_days_back )
{
    die( "$days_back days is too far back" );
}

echo "<h2>Projects with pages whose latest event was a saveAsDone by '$username' in the last $days_back days</h2>\n";

$min_timestamp = time() - $days_back * 86400;
$event_id = get_least_event_id_with_timestamp( $min_timestamp );

dpsql_query("
    CREATE TEMPORARY TABLE _page_latest
    SELECT projectid, image, round_id, max(event_id) AS event_id
    FROM page_events
    WHERE event_id >= $event_id
        AND round_id IS NOT NULL
    GROUP BY projectid, image, round_id
");

dpsql_dump_query("
    SELECT
        page_events.round_id,
        COUNT(*) as num_pages,
        projects.nameofwork,
        projects.state
    FROM page_events
        NATURAL JOIN _page_latest
        JOIN projects USING (projectid)
    WHERE page_events.username='$username' AND page_events.event_type='saveAsDone'
    GROUP BY page_events.round_id, page_events.projectid
    ORDER BY page_events.round_id DESC, num_pages DESC
");

// vim: sw=4 ts=4 expandtab
?>
