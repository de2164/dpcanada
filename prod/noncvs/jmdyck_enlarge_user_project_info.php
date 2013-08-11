<?PHP
$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'user_project_info.inc'); // upi_ensure_row
new dbConnect;

assert_options(ASSERT_BAIL, 1);

$watch = new Stopwatch;
$watch->start();

$res = mysql_query("
    SELECT projectid, archived, state
    FROM projects
    ORDER BY projectid
") or die(mysql_error());

$t1 = $watch->read();

$n_projects = mysql_num_rows($res);
$i = 0;
while ( list($projectid, $archived, $state) = mysql_fetch_row($res) )
{
    $i++;
    echo sprintf( "%5d/%5d (%6.2f): %s", $i, $n_projects, 100*$i/$n_projects, $projectid );

    init_user_data();

    process_page_table( $projectid, $archived, $state );

    process_page_events_table( $projectid );

    flush_user_data( $projectid );
    // break;

}
assert($i == $n_projects);

$t2 = $watch->read();

echo "\n";
echo "query: $t1\n";
echo "loop: ", $t2 - $t1, "\n";
exit;

// ------------------------------------------------------------------------------

function process_page_table( $projectid, $archived, $state )
{
    // echo "\nprocess_page_table...\n";

    global $db_name, $archive_db_name;
    echo " $archived";
    assert( $archived === '0' || $archived === '1' );
    $dbn = ($archived === '0' ? $db_name : $archive_db_name );
    // echo " $dbn";
    $res2 = mysql_query("
        DESCRIBE $dbn.$projectid
    ");
    if ($res2 === FALSE)
    {
        echo " NO PAGE TABLE ($state)\n";
        assert( $state == 'proj_submit_pgposted' || $state == 'project_delete');
        $other_dbn = ($archived === '1' ? $db_name : $archive_db_name );
        assert( mysql_query("DESCRIBE $other_dbn.$projectid") === FALSE);
        return;
    }

    $n_cols = mysql_num_rows($res2);
    $n_round_slots = floor( ($n_cols-6) / 3 );
    echo " page table has $n_cols columns, $n_round_slots rounds";

    $items = "image, state";
    for ( $r = 1; $r <= $n_round_slots; $r++ )
    {
        $items .= ", round{$r}_time, round{$r}_user";
    }
    $res3 = mysql_query("
        SELECT $items
        FROM $dbn.$projectid
    ") or die(mysql_error());
    $n_pages = mysql_num_rows($res3);
    echo " x $n_pages pages";
    echo "\n";
    while ( $row = mysql_fetch_assoc($res3) )
    {
        $image = $row['image'];
        $page_state = $row['state'];
        if (0) echo "$image $page_state\n";

        // Translate $page_state into $page_r and $page_substate
        if ( $n_round_slots == 2 )
        {
            $map = array(
                'avail_second' => array(2,'page_avail'), // unusual
                'save_second'  => array(2,'page_saved'),
                'bad_second'   => array(2,'page_bad'),
            );
            list($page_r,$page_substate) = $map[$page_state];
            assert(!is_null($page_r));
        }
        else
        {
            $a = explode('.', $page_state);
            if (count($a) != 2) print "!$page_state!\n";
            assert(count($a) == 2);
            list($page_round_id, $page_substate) = $a;

            if ( $n_round_slots == 4 )
            {
                $slot_map = array('P1'=>1, 'P2'=>2, 'F1'=>3, 'F2'=>4);
            }
            elseif ( $n_round_slots == 5 )
            {
                $slot_map = array('P1'=>1, 'P2'=>2, 'P3'=>3, 'F1'=>4, 'F2'=>5);
            }
            else
            {
                echo "$n_round_slots\n";
                assert(0);
            }
            $page_r = $slot_map[$page_round_id];
        }

        for ( $r = 1; $r <= $n_round_slots; $r++ )
        {
            $time = $row["round{$r}_time"];
            $user = $row["round{$r}_user"];
            if (0) echo "   $r $time $user\n";

            if ( $user == '' )
            {
                if ( $r < $page_r )
                {
                    // Project skipped round $r
                }
                elseif ( $r == $page_r )
                {
                    // assert( $time != 0 ); // a few odd exceptions
                    /*
                        || $projectid == 'projectID4147675eb022f'
                        || $projectid == 'projectID416c372584145'
                        || $projectid == 'projectID41814c8fb48e0'
                        || $projectid == 'projectID41c5aecea9eed'
                        projectID42d2230a6d255
                        maybe more
                    */
                    if ( $projectid=='projectID3e19c28bdad92' && $image=='052.png' )
                    {
                        // $page_substate == 'page_saved', very odd.
                    }
                    else
                    {
                        assert( $page_substate == 'page_avail' );
                    }
                }
                else
                {
                    // assert( $time == 0 ); can't because project might have been sent back
                }
                continue;
            }

            $incr_n_pages_out  = FALSE;
            $incr_n_pages_done = FALSE;

            if ( $r < $page_r )
            {
                // Page must have been saved in round $r by $user at $time.
                $incr_n_pages_done = TRUE;
            }
            else if ( $r == $page_r )
            {
                if ( $page_substate == 'page_avail' )
                {
                    // Usually, $user == '',
                    // but possibly $user != '' if user returned page to round.
                    // In latter case, it's correct to record t_last_page_event.
                }
                elseif ( $page_substate == 'page_out' || $page_substate == 'page_temp' )
                {
                    $incr_n_pages_out = TRUE;
                }
                elseif ( $page_substate == 'page_saved' )
                {
                    $incr_n_pages_done = TRUE;
                }
                elseif ( $page_substate == 'page_bad' )
                {
                }
                else
                {
                    echo "$image $page_substate";
                    assert(0);
                }
            }
            else
            {
                // $r > $page_r
                // assert( $time == 0 ); can't because project might have been "sent back" a round
                assert( 0
                    || $projectid=='projectID461af7c694a30' // 'Little Miss Grouch' not sure what happened
                );
            }

            record_some_user_data( $user, $time, $time, $incr_n_pages_out, $incr_n_pages_done );
        }
        if (0) echo "\n";
    }
}

function process_page_events_table( $projectid )
{
    // echo "\nprocess_page_events_table...\n";
    $res = mysql_query("
        SELECT username, MIN(timestamp), MAX(timestamp)
        FROM page_events
        WHERE projectid='$projectid'
            AND event_type IN ('checkout','saveAsInProgress','saveAsDone','reopen','returnToRound','markAsBad')
        GROUP BY username
    ") or die(mysql_error());

    // echo "\n";
    while ( list($username,$min_timestamp,$max_timestamp) = mysql_fetch_row($res) )
    {
        // echo "    $min_timestamp $max_timestamp $username\n";
        record_some_user_data($username, $min_timestamp, $max_timestamp, FALSE, FALSE );
    }
}

// -----------------------------------------

function init_user_data()
{
    global $user_data;
    $user_data = array();

    global $t_last_page_done;
    $t_last_page_done = 0;
}

function record_some_user_data(
    $username,
    $t_first_page_event,
    $t_last_page_event,
    $incr_n_pages_out,
    $incr_n_pages_done )
{
    global $user_data;
    $s =& $user_data[$username];

    if ( is_null($s) )
    {
        $s =& new stdClass;
        $s->t_first_page_event = $t_first_page_event;
        $s->t_last_page_event  = $t_last_page_event;
        $s->n_pages_out  = 0;
        $s->n_pages_done = 0;
        $user_data[$username] =& $s;
    }
    else
    {
        // $s->t_first_page_event = min($s->t_first_page_event, $t_first_page_event);
        if ( $t_first_page_event < $s->t_first_page_event )
        {
            // echo "$username: min $t_first_page_event < $s->t_first_page_event\n";
            $s->t_first_page_event = $t_first_page_event;
        }
        $s->t_last_page_event  = max($s->t_last_page_event,  $t_last_page_event);
    }
    if ( $incr_n_pages_out  ) $s->n_pages_out++;
    if ( $incr_n_pages_done ) $s->n_pages_done++;

    // bit of a hack
    if ( $incr_n_pages_done ) 
    {
        global $t_last_page_done;
        $t_last_page_done = max( $t_last_page_done, $t_last_page_event );
    }
}

function flush_user_data( $projectid )
{
    global $user_data;
    global $t_last_page_done;
    if (0)
    {
        echo "---\n";
        foreach( $user_data as $username => $s )
        {
            $x = print_r($s,TRUE);
            $x = preg_replace('/\s+/', ' ', $x);
            echo "  $username =>\n    $x\n";
        }
        echo "\n";

        echo "t_last_page_done: $t_last_page_done\n";
    }
    elseif (1)
    {
        $echo_queries = FALSE;
        $do_queries = TRUE;

        foreach( $user_data as $username => $s )
        {
            // For t_latest_home_visit, take a guess:
            // either a minute before the first event,
            // or a day before the last event,
            // whichever is later.
            $t_latest_home_visit = max(
                $s->t_first_page_event - 60,
                $s->t_last_page_event - 24*60*60
            );
            /*
            $q = "
                UPDATE user_project_info
                SET
                    t_latest_home_visit = $t_latest_home_visit,
                    t_latest_page_event = {$s->t_last_page_event}
                    -- , n_pages_out        = {$s->n_pages_out}
                    -- , n_pages_done       = {$s->n_pages_done}
                WHERE username='$username' AND projectid='$projectid'
            "; 
            */
            $q = "
                INSERT INTO user_project_info
                SET
                    username    = '$username',
                    projectid   = '$projectid',
                    t_latest_home_visit = $t_latest_home_visit,
                    t_latest_page_event = {$s->t_last_page_event}
                ON DUPLICATE KEY UPDATE
                    t_latest_home_visit = $t_latest_home_visit,
                    t_latest_page_event = {$s->t_last_page_event}
            ";
            if ($echo_queries) echo "$q\n";
            if ($do_queries)
            {
                // upi_ensure_row( $username, $projectid );
                mysql_query($q) or die(mysql_error());
            }
        }

        if ( $t_last_page_done > 0 )
        {
            $q = "
                UPDATE projects
                SET t_last_page_done = '$t_last_page_done'
                WHERE projectid='$projectid'
            ";
            if ($echo_queries) echo "$q\n";
            if ($do_queries)
            {
                mysql_query($q) or die(mysql_error());
            }
        }
    }
}

// ---------------------------------------------------------


/*
  $n_cols == 6 (image,fileid,master_text,state,b_user,b_code) + n_rounds * 3 (time,user,text) + maybe 2 (metadata,orig_page_num)
  where n_rounds in [2,4,5]
  [12]=> int(1772)
  [14]=> int(1664)
  [18]=> int(309)
  [20]=> int(1412)
  [21]=> int(1570)
  [23]=> int(6557)
  so $n_rounds = floor( ($n_cols-6) / 3 );
*/

exit;

$n_rows = 10000;

$res = mysql_query("
    -- SELECT timestamp, projectid, username
    -- FROM page_events
    -- LIMIT $n_rows
    SELECT projectid,username,MAX(timestamp)
    FROM page_events
    WHERE event_type IN ('checkout', 'saveAsInProgress', 'saveAsDone', 'reopen', 'returnToRound', 'markAsBad')
    GROUP BY projectid,username
    LIMIT $n_rows
") or die(mysql_error());
$t1 = $watch->read();

$dummy = 0;
while ( $row = mysql_fetch_row($res) )
{
    $dummy ++;
}

$t2 = $watch->read();

assert( $dummy == mysql_num_rows($res) );

echo $t1, " for mysql_query\n";
echo $n_rows/$t1, " rows/sec\n";
echo $t2-$t1, " for loop\n";

/*
    SELECT timestamp, projectid, username FROM page_events LIMIT x:

        x         t (sec) rows/sec
        100       0.08     1300
        1,000     0.10     9796
        10,000    0.65    15445
        20,000    0.78    25785
        30,000    1.76    17049
        100,000  45.92     2178

select count(*) from page_events
13863553

select distinct projectid,username from page_events
289084 rows in set (3 min 5.61 sec)
[avg 48 events per distinct pair]
[selecting all events for a given pair might take avg 0.05 sec? total=14454=4hr]

select distinct projectid from page_events;
8540 rows in set (36.45 sec)
[avg 1623 events per project]
[selecting all events for a given project might take avg 0.5 sec? total=4270=1.2hr]

select distinct username from page_events;
12925 rows in set (21.89 sec)
[avg 1073 events per user]
[selecting all events for a given user might take avg 0.5 sec?]

projectID3c1bfeebdccac | ajhickford  |
| projectID3c1bfeebdccac | alalk33     |
| projectID3c1bfeebdccac | AliSarah    |
| projectID3c1bfeebdccac | brucedt     |
| projectID3c1bfeebdccac | ceb         |
| projectID3c1bfeebdccac | celestial13 |
| projectID3c1bfeebdccac | Charron     |
| projectID3c1bfeebdccac | Close@Hand  |
| projectID3c1bfeebdccac | De2164      |
| projectID3c1bfeebdccac | dkretz      |

-----------------------------

 but this was during backup, should try again later
  100  1.52  65.78
  316  2.00 158.17
 1000 12.07  82.86
 3162 20.94 151.01
10000 51.72 193.33

*/



// vim: sw=4 ts=4 expandtab
?>
