<?PHP

// Look for odd page-transitions

$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
if ( php_sapi_name() == 'cli' )
{
    include_once('./cli.inc');
    include_once($relPath.'connect.inc');
    new dbConnect;
}
else
{
    include_once($relPath.'dp_main.inc');
    if (!user_is_a_sitemanager()) die("permission denied");
}

/*
      1 avail+add
      1 avail+eraseBadMark
      1 bad+markAsBad
      1 bad+saveAsInProgress
      1 NONEXISTENT+delete
      1 out+checkout
      1 save+saveAsDone*
      1 temp+reopen
      2 avail+reopen
      2 avail+saveAsInProgress
      4 avail+clearRound
      6 avail+saveAsDone
      9 save+saveAsInProgress
     11 save+returnToRound
     30 avail+returnToRound
*/

error_reporting(E_ALL);

header('Content-type: text/plain');

if (1)
{
    $page_event_partial_condition = "1";
    // $page_event_partial_condition = "username='$username' AND round_id='$round_id'";
    // $page_event_partial_condition = "projectid='projectID429b9a38ba7a8'";
    // $page_event_partial_condition = "projectid='projectID453ef5cc9b20d'";
    // $page_event_partial_condition = "projectid='projectID43a8eb5763c1
    // $page_event_partial_condition = "projectid='projectID4564a1936f71b'";
    // $page_event_partial_condition = "projectid='projectID41c6def92c191'";
    // $page_event_partial_condition = "projectid='projectID3fb7d64380bda'";
    // $page_event_partial_condition = "projectid='projectID45abf21f42120'"; // The Life of Mo?ammad
    // $page_event_partial_condition = "projectid='projectID45d3397e662af'"; // Studi intorno alla Storia della Lombardia
    $page_event_partial_condition = "projectid='projectID48648367c1d8d'"; // Studi intorno alla Storia della Lombardia

    $min_timestamp = 0;
    // $min_timestamp = strtotime('June 1, 2005'); // earliest rows in page_events
    // $min_timestamp = strtotime('18:05 Dec 29, 2005');
    // $min_timestamp = strtotime('00:00 Sep 03, 2006');

    // $max_timestamp = strtotime('June 20, 2005');
    // $max_timestamp = strtotime('August 1, 2005');
    $max_timestamp = strtotime('Jan 1, 2009');

    something( "
        ($page_event_partial_condition)
        AND timestamp >= $min_timestamp
        AND timestamp <  $max_timestamp
    ");
}
else
{
    // $project_condition = "projectid in ('projectID429b9a38ba7a8', 'projectID453ef5cc9b20d')";
    $project_condition = 1;
    $res1 = dpsql_query("
        SELECT projectid
        FROM projects
        WHERE archived = 0
            AND ($project_condition)
        ORDER BY modifieddate DESC
    ") or die("Aborting");

    while( list($projectid) = mysql_fetch_row($res1) )
    {
        echo "\n$projectid\n";

        something( "projectid='$projectid'" );
    }
}

// ----------------------------------------------------------------------------

function something( $page_event_condition )
{
    $trace = FALSE;
    // $trace = TRUE;

    $flag_zero_timestamp_diff = FALSE;

    $x = array(
        "NONEXISTENT+add"       => "avail",

        "avail+delete"          => "NONEXISTENT",
        "avail+replaceText"     => "avail",
        "avail+modifyText"      => "avail",
        "avail+checkout"        => "out",

        "bad+eraseBadMark"      => "avail",

        "out+reclaim"           => "avail",
        "out+returnToRound"     => "avail",
        "out+markAsBad"         => "bad",
        "out+saveAsInProgress"  => "temp",
        "out+saveAsDone"        => "save",

        "temp+reclaim"          => "avail",
        "temp+returnToRound"    => "avail",
        "temp+markAsBad"        => "bad",
        "temp+saveAsInProgress" => "temp",
        "temp+saveAsDone"       => "save",

        "save+reopen"           => "temp",
        "save+clearRound"       => "avail",
        "save+delete"           => "NONEXISTENT",

        // Bad ones:
        // "save+saveAsDone"       => "save",
    );

    $res = dpsql_query("
        SELECT *
        FROM page_events
        WHERE $page_event_condition
        ORDER BY projectid, image, event_id
    ") or die("Aborting");

    $current_projectid = NULL;
    while( $event = mysql_fetch_object($res) )
    {
        if ( $event->projectid != $current_projectid )
        {
            // First event for this project
            $current_projectid = $event->projectid;
            $current_image = NULL;
        }
        if ( $event->image != $current_image )
        {
            // First event for this image.
            $current_image = $event->image;
            $skip_the_rest_of_this_image = FALSE;

            // If we were starting from scratch, we'd just say
            // $page_state = 'NONEXISTENT';
            // but there were a lot of pages on the go when the page_events table started.
            // (And now there's $min_timestamp.)
            // Make up a plausible current state for the page.
            switch ( $event->event_type )
            {
                case 'add':              $page_state = 'NONEXISTENT'; break;
                case 'eraseBadMark':     $page_state = 'bad';   break;
                case 'modifyText':       $page_state = 'avail'; break;
                case 'replaceText':      $page_state = 'avail'; break;
                case 'clearRound':       $page_state = 'avail'; break;
                case 'checkout':         $page_state = 'avail'; break;
                case 'delete':           $page_state = 'avail'; break;
                case 'reclaim':          $page_state = 'temp';  break;
                case 'reopen':           $page_state = 'save';  break;
                case 'saveAsDone':       $page_state = 'temp';  break;
                case 'saveAsInProgress': $page_state = 'temp';  break;
                case 'returnToRound':    $page_state = 'temp';  break;
                default:                 $page_state = 'unknown_cutover_state'; break;
            }

            $current_round_id = $event->round_id;

            $prev_timestamp = $event->timestamp - 1000;
        }
        else
        {
            // Not the first event for this image
            if ( !is_null($event->round_id) && $event->round_id != $current_round_id )
            {
                // First event for this image in this round.
                $current_round_id = $event->round_id;
                $page_state = 'avail';
            }
        }

        if ( $skip_the_rest_of_this_image )
        {
            continue;
        }
        $trigger = "$page_state+$event->event_type";
        $page_state = @$x[$trigger];
        if ( $trace
            || empty($page_state)
            || $flag_zero_timestamp_diff && $event->timestamp == $prev_timestamp
        )
        {
            echo sprintf(
                "%7s %s %-12s %s (%6d) %s %s %s -> %s\n",
                $event->event_id,
                $event->projectid,
                $event->image,
                strftime('%Y-%m-%d %H:%M:%S', $event->timestamp),
                $event->timestamp - $prev_timestamp,
                $event->round_id,
                $event->username,
                $trigger,
                ( empty($page_state) ? "NO TRANSITION" : $page_state )
            );
            if ( empty($page_state) ) $skip_the_rest_of_this_image = TRUE;
        }

        $prev_timestamp = $event->timestamp;
    }
}

// vim: sw=4 ts=4 expandtab
?>
