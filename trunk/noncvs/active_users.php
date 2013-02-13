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
include_once('page_events.inc');

if ( $pguser != 'jmdyck' ) die("This script is too resource-intensive (page_events).");

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

$n_proofers = 20;

echo "<p>";
echo "Click on a user's name to get a list of the projects that the user has worked on recently (in the given round).";
echo "</p>";
echo "\n";

foreach ( $Round_for_round_id_ as $round )
{
    echo "<h3>";
    echo "Most active $n_proofers users in $round->id in the last [d] days";
    echo "</h3>";
    echo "\n";

    echo "<table border='1'>";
    echo "<tr>";
    foreach ( array(1,7 /*,14 */) as $num_days )
    {
        echo "<td>";
        echo "d = $num_days\n";

        $event_id = get_least_event_id_with_timestamp( time() - ($num_days * 24 * 60 * 60) );

        dpsql_dump_query("
            SELECT
                REPLACE('<a href=\"user_in_round.php?username=USER&round_id=$round->id\">USER</a>', 'USER', username)
                    AS username,
                SUM(event_type='saveAsDone') - SUM(event_type='clear' OR event_type='reopen')
                    AS n_saved
            FROM page_events
            WHERE
                event_id >= $event_id
                AND round_id='$round->id'
            GROUP BY username
            ORDER BY n_saved DESC
            LIMIT $n_proofers
        ");
        echo "</td>";
    }
    echo "</tr>";
    echo "</table>";
}

// vim: sw=4 ts=4 expandtab
?>
