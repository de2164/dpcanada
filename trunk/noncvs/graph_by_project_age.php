<?PHP

$relPath='../c/pinc/';
include_once('page_events.inc'); // get_least_event_id_with_timestamp

if ( php_sapi_name() == 'cli' )
{
    include_once($relPath.'connect.inc');
    new dbConnect;
}
else
{
    include_once($relPath.'dp_main.inc');
    include_once($relPath.'user_is.inc');
    include_once($relPath.'../stats/jpgraph_files/common.inc');

    if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() ) die("not permitted");

    $which = @$_GET['which'];
    if ( $which != 'net_saves' && $which != 'avg_tally' && $which != 'something' )
    {
        die("bad value for 'which' parameter: '$which'");
    }
}

switch( $which )
{
    case 'net_saves': $height = 350; break;
    case 'avg_tally': $height = 500; break;
    case 'something': $height = 400; break;
    default: assert(0);
}
$graph = init_simple_bar_graph(640, $height, 60);

if ( $which != 'net_saves' ) sleep(20);

// -----------------------------------------------------------------------------

$trace = FALSE;

$round_id = 'P1';

$t_start_of_today = strtotime("00:00:00 today");
$t_start_of_interval = strtotime("00:00:00 yesterday");

// -----------------------------------------------------------------------------

{
    $release_timestamp_ = array();
    $res = mysql_query("
        SELECT projectid, MAX(timestamp)
        FROM project_events
        WHERE
            event_type = 'transition'
            AND details1 = '$round_id.proj_waiting'
            AND details2 = '$round_id.proj_avail'
        GROUP by projectid
    ") or die(mysql_query());
    while( list($projectid,$release_timestamp) = mysql_fetch_row($res) )
    {
        $release_timestamp_[$projectid] = $release_timestamp;
    }
    if ($trace) echo "release_timestamp_: ", count($release_timestamp_), "\n";
}

{
    $tally_value_ = array();
    $res = mysql_query("
        SELECT username, tally_value
        FROM past_tallies
            JOIN users
            ON (holder_id = u_id)
        WHERE
            tally_name='$round_id'
            AND holder_type='U'
            AND timestamp=$t_start_of_today
    ") or die(mysql_query());
    while( list($username,$tally_value) = mysql_fetch_row($res) )
    {
        $tally_value_[$username] = $tally_value;
    }
    if ($trace) echo "tally_value_: ", count($tally_value_), "\n";
}

// -----------------------------------------------------------------------------

$first_event_id_of_interval = get_least_event_id_with_timestamp( $t_start_of_interval );
$last_event_id_of_interval  = get_least_event_id_with_timestamp( $t_start_of_today ) - 1;
if ($trace) echo "$first_event_id_of_interval - $last_event_id_of_interval\n";

$res = mysql_query("
    SELECT *
    FROM page_events
    WHERE event_id BETWEEN $first_event_id_of_interval AND $last_event_id_of_interval
    -- AND event_type IN ('saveAsDone','reopen')
    -- AND round_id='$round_id'
") or die(mysql_error());

if ($trace) echo mysql_num_rows($res), " page events in that interval\n";

// Seems faster to filter the events in PHP than in SQL.

$net_pages_saved_ = array();
$round_tally_num_ = array();
$round_tally_den_ = array();
$something = array();
while ( $event = mysql_fetch_object($res) )
{
    if ( $event->round_id == $round_id )
    {
        if ( $event->event_type == 'saveAsDone' )
            $sign = +1;
        elseif ( $event->event_type == 'reopen' )
            $sign = -1;
        else
            continue;

        // The age of the project at the time of the event:
        $project_age_in_seconds = $event->timestamp - $release_timestamp_[$event->projectid];
        // Age in weeks (rounded up).
        $project_age_in_weeks = ceil( $project_age_in_seconds / (7*24*60*60) );

        $net_pages_saved_[$project_age_in_weeks] += $sign;

        // The user's $round_id tally (as of the end of the study interval):
        $user_round_tally = $tally_value_[$event->username];

        $round_tally_num_[$project_age_in_weeks] += $sign * $user_round_tally;

        if ( $project_age_in_weeks == 1 )
        {
            $x = floor($user_round_tally / 100);
            $something[$x] += $sign;
        }

        if (0)
        {
            if ($project_age_in_weeks == 1 )
            {
                echo "$event->username\t$user_round_tally\t$event->projectid\t$sign\n";
            }
        }
    }
}

ksort($net_pages_saved_);
ksort($round_tally_num_);

if ($which == 'net_saves')
{
    draw_simple_bar_graph(
        $graph,
        array_keys($net_pages_saved_),
        array_values($net_pages_saved_),
        1,
        "$round_id-age of projects in weeks (rounded up)",
        "net pages saved in $round_id"
    );
}

if ($which == 'avg_tally')
{
    $avg_round_tally = array();
    foreach( $round_tally_num_ as $project_age_in_weeks => $tally_num )
    {
        $avg_round_tally_[$project_age_in_weeks] = $tally_num / $net_pages_saved_[$project_age_in_weeks];
    }

    draw_simple_bar_graph(
        $graph,
        array_keys($avg_round_tally_),
        array_values($avg_round_tally_),
        1,
        "$round_id-age of projects in weeks (rounded up)",
        "weighted avg $round_id-tally for proofers saving pages in $round_id"
    );
}

if ($which == 'something')
{
    ksort($something);

    $labels = array();
    foreach( $something as $x => $count )
    {
        $labels[] = "{$x}00 - {$x}99";
    }

    draw_simple_bar_graph(
        $graph,
        $labels,
        array_values($something),
        1,
        "proofer's $round_id page count (axis is not linear)",
        "# pages saved in first-week $round_id projects"
    );
}

// vim: sw=4 ts=4 expandtab
?>
