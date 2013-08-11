<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

$username='JHowse';
$round_id='F2';

if (0)
{
    // Look for projects named in event_projects table
    // that do not have an entry in 'projects' table.

    dpsql_query("
        CREATE TEMPORARY TABLE event_projects
        SELECT DISTINCT projectid
        FROM page_events
    ");

    dpsql_dump_query("
        SELECT event_projects.projectid
        FROM event_projects LEFT OUTER JOIN projects USING (projectid)
        WHERE projects.projectid IS NULL
    ");
    // should show nothing

    exit;
}


if (0)
{
    // Count pages in page-tables.

    $res = dpsql_query("
        SELECT DISTINCT projectid
        FROM page_events
        WHERE username='$username' AND round_id='$round_id'
    ");

    $cutoff = strtotime('2005-06-10 00:00:00');

    $round = get_Round_for_round_id($round_id);
    $round->user_column_name;

    $total = 0;
    echo "<pre>";
    while ( list($projectid) = mysql_fetch_row($res) )
    {
        echo "$projectid";
        $res2 = dpsql_query("
            SELECT COUNT(*)
            FROM $projectid
            WHERE {$round->user_column_name} = '$username'
            AND {$round->time_column_name} < $cutoff
        ");
        if ( !$res2 ) continue;
        $n = mysql_result($res2,0);
        echo " $n";
        echo "\n";
        $total += $n;
    }
    echo "$total\n";
    exit;
}

if (0)
{
    // Show any events (in this round) pertaining to pages that she worked on.
    // Simulate her page-count over time.

    dpsql_query("
        CREATE TEMPORARY TABLE something
        SELECT DISTINCT projectid, image, round_id
        FROM page_events
        WHERE username='$username' AND round_id='$round_id'
    ") or die("Aborting");

    $res = dpsql_query("
        SELECT page_events.*
        FROM page_events JOIN something USING(projectid,image,round_id)
        ORDER BY page_events.event_id
    ");

    echo "<pre>\n";

    $tally = 0;
    while ( $event = mysql_fetch_object($res) )
    {
        // var_dump( $event );
        echo strftime('%Y-%m-%d %H:%M:%S', $event->timestamp), " $event->projectid $event->image $event->event_type\n";
        if ( $event->username=$username )
        {
            switch ( $event->event_type )
            {
                case 'saveAsDone':
                    $tally++;
                    echo "              tally +1 to $tally\n";
                    break;

                case 'reopen':
                    $tally--;
                    echo "              tally -1 to $tally\n";
                    break;

                case 'checkout':
                case 'saveAsInProgress':
                case 'returnToRound':
                    break;

                default:
                    echo $event->event_type, "\n";
                    break;
            }
        }
        else
        {
            var_dump($event); echo "\n";
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
