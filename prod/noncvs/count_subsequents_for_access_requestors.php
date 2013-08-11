<?PHP

// There's a cron job that runs this once a week and sends the output to JHowse.

$relPath='../c/pinc/';
if ( php_sapi_name() == 'cli' )
{
    include_once('cli.inc');
    include_once($relPath.'connect.inc');
    new dbConnect;
}
else
{
    include_once($relPath.'dp_main.inc');

    if (!user_is_a_sitemanager()) die("permission denied");
}
include_once($relPath.'dpsql.inc');
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // array_intersect_key

error_reporting(E_ALL & E_NOTICE);

// --------------------------------------------

$work_round_id = 'F1';
$review_round_id = 'F2';
$max_days_ago_for_work_save = 150;

// ------

$t_work_save_horizon = time() - $max_days_ago_for_work_save * 24*60*60;
// Any save (in the work round) before $t_work_save_horizon is too old
// to be a useful indicator of the user's skill.

$stopwatch = new Stopwatch;
$stopwatch->start();

$progressed_projectids = get_progressed_projects();

$requestors = get_requestors();

if (1)
{
    $rw_projectids = get_requestor_worked_projects($requestors);

    $projectids_to_look_at = array_intersect_key( $rw_projectids, $progressed_projectids );
    echo sprintf( "Of %s requestor-worked projects, %d have progressed.\n",
        count($rw_projectids),
        count($projectids_to_look_at) );
}
else
{
    $projectids_to_look_at = array(
        'projectID3fb3e267833db' => 1,
        'projectID40f8590ac53bd' => 1,
        'projectID41567e94a62b8' => 1,
        'projectID437ed730db8de' => 1,
        'projectID455667b378299' => 1,
        'projectID45aad82de36d3' => 1,
        'projectID45ab0229083b6' => 1,
        'projectID45d6a4717ba86' => 1,
        'projectID460120efaab57' => 1,
        'projectID463a4acb0f0f0' => 1,
        'projectID466ca62de032f' => 1,
        'projectID4684742a7af36' => 1,
        'projectID46aeb857ecfb0' => 1,
    );
}

$projectids_to_look_at = adjust_for_merges($projectids_to_look_at);

look_at_projects($requestors, $projectids_to_look_at);

// --------------------------------------------

function get_requestors()
// Return an array whose keys are usernames,
// indicating the users who have requested access to $review_round.
// The corresponding value is the time of the latest denial.
{
    global $work_round_id, $review_round_id;

    echo "\n";
    echo "get_requestors()\n";

    $res = mysql_query("
        SELECT DISTINCT username
        FROM usersettings
        WHERE setting = '$review_round_id.access' AND value='requested'
        ORDER BY username
        -- limit 11
    ") or die(mysql_error());
    $n_requestors = mysql_num_rows($res);
    echo "$n_requestors requestors\n";

    $requestors = dpsql_fetch_all_keyed($res);
    foreach ( $requestors as $requestor => $_ )
    {
        $requestors[$requestor] = 0;
    }

    $res = mysql_query("
        SELECT subject_username, MAX(timestamp)
        FROM access_log
        WHERE activity='F2' and action='deny_request_for'
        GROUP BY subject_username
    ") or die(mysql_erro());
    $denial_times = dpsql_fetch_all_keyed($res);

    foreach ( $denial_times as $username => $ltimestamp )
    {
        if ( array_key_exists($username, $requestors) )
        {
            list($latest_denial_time) = $ltimestamp;
            $requestors[$username] = $latest_denial_time;
        }
    }

    // var_dump($requestors);

    return $requestors;
}

function get_requestor_worked_projects($requestors)
// Return an array whose keys are projectids, indicating the projects
// that requestors worked on in the work round.
// Values are whatever.
{
    global $work_round_id, $review_round_id, $t_work_save_horizon, $stopwatch;

    echo "\n";
    echo "get_requestor_worked_projects()\n";

    $t_before = $stopwatch->read();

    if (1)
    {
        // This alternative does lots of short queries,
        // and so doesn't lock the page_events table for long.

        $rw_projectids = array();

        foreach ( $requestors as $username => $latest_denial_time )
        {
            echo "$username";

            $min_timestamp = max($latest_denial_time, $t_work_save_horizon);

            $res2 = mysql_query("
                SELECT DISTINCT projectid
                FROM page_events
                WHERE username='$username'
                    AND event_type='saveAsDone'
                    AND round_id='$work_round_id'
                    AND timestamp > $min_timestamp
            ") or die(mysql_error());
            echo " ", mysql_num_rows($res2);

            while ( list($projectid) = mysql_fetch_row($res2) )
            {
                $rw_projectids[$projectid] = 1;
            }
            echo "\n";
        }
    }
    else
    {
        // This might be faster, but it would spend a long time in a single query,
        // (probably several minutes) which might delay a lot of page-saves.
        $s = surround_and_join($usernames, "'", "'", ", ");
        echo "$s\n";

        $res2 = mysql_query("
            SELECT DISTINCT projectid
            FROM page_events
            WHERE username IN ($s)
                AND event_type='saveAsDone'
                AND round_id='$work_round_id'
        ") or die(mysql_error());
        $rw_projectids = dpsql_fetch_all_keyed($res2);
    }

    $t_after = $stopwatch->read();
    $t_average = ( $t_after - $t_before ) / count($requestors);
    echo "average time to get projectids per requestor: $t_average\n";
    // is about 9 sec.

    echo count($rw_projectids), " requestor-worked projectids\n";
    return $rw_projectids;
}

function get_progressed_projects()
// Get all unarchived projects that have left work-round
// and have become available in review-round.
{
    global $work_round_id, $review_round_id, $stopwatch;

    echo "\n";
    echo "get_progressed_projects()\n";

    $t_before = $stopwatch->read();
    $res = mysql_query("
        SELECT DISTINCT projectid
        FROM
            project_events pe1
            JOIN project_events pe2 USING (projectid)
            JOIN projects USING (projectid)
        WHERE
            pe1.event_type = 'transition'
            AND pe1.details1 = '$work_round_id.proj_avail'
            AND pe1.details2 = '$work_round_id.proj_done'
            AND
            pe2.event_type = 'transition'
            AND pe2.details1 = '$review_round_id.proj_waiting'
            AND pe2.details2 = '$review_round_id.proj_avail'
            AND
            projects.archived = 0
        ORDER BY projectid
    ") or die(mysql_error());
    $t_after = $stopwatch->read();
    $t_diff = $t_after - $t_before;
    echo "time to get progressed projects: $t_diff\n"; // 20-30 sec?

    echo mysql_num_rows($res), " progressed projects\n";

    $progressed_projectids = dpsql_fetch_all_keyed($res);

    return $progressed_projectids;
}

function adjust_for_merges($projectids)
{
    echo "\n";
    echo "adjust_for_merges()\n";

    $resulting_projectids = array();
    foreach( $projectids as $projectid => $_ )
    {
        $res = mysql_query("
            SELECT state, deletion_reason
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());
        list( $state, $deletion_reason ) = mysql_fetch_row($res);
        if ( $state == 'project_delete' )
        {
            if ( startswith($deletion_reason,'merged into') )
            {
                $res_projectid = preg_replace('/^merged into /','', $deletion_reason);
                $resulting_projectids[$res_projectid] = 1;
            }
            else
            {
                echo "$projectid deleted, not merged\n";
            }
        }
        else
        {
            $resulting_projectids[$projectid] = 1;
        }
    }

    echo "adjust_for_merges: before ", count($projectids), ", after ", count($resulting_projectids), "\n";

    return $resulting_projectids;
}

function look_at_projects($requestors, $projectids)
{
    global $work_round_id, $review_round_id, $t_work_save_horizon, $stopwatch;
    global $max_days_ago_for_work_save;
    global $code_url;
    global $n_rounds;

    echo "\n";
    echo "look_at_projects()\n";

    $t_before = $stopwatch->read();

    echo count($projectids), " projects to look at\n";
    ksort($projectids);

    $requestor_info = array();
    foreach ( $requestors as $requestor => $_ )
    {
        $requestor_info[$requestor] = array( 0, array() );
    }

    $work_round = get_Round_for_round_id($work_round_id);
    $review_round = get_Round_for_round_id($review_round_id);

    $good_states = array($review_round->page_save_state => 1);
    for ( $ri = $review_round->round_number+1; $ri <= $n_rounds; $ri++ )
    {
        $round = get_Round_for_round_number($ri);
        $good_states[$round->page_avail_state] = 1;
        $good_states[$round->page_out_state]   = 1;
        $good_states[$round->page_temp_state]  = 1;
        $good_states[$round->page_save_state]  = 1;
        $good_states[$round->page_bad_state]   = 1;
    }

    foreach( $projectids as $projectid => $_ )
    {
        // echo "$projectid\n";

        $res2 = mysql_query("
            SELECT
                image,
                state,
                {$work_round->user_column_name},
                {$work_round->time_column_name}
            FROM $projectid
            ORDER BY image
        ") or die(mysql_error());
        // echo "    ", mysql_num_rows($res2), "<br>\n";
        $counts_for_this_project = array();
        while( list($image, $state, $work_user, $work_time ) = mysql_fetch_row($res2) )
        {
            if ( array_key_exists($work_user, $requestors) )
            {
                $latest_denial_time = $requestors[$work_user];

                $min_timestamp = max($latest_denial_time, $t_work_save_horizon);

                // only count pages that:
                // a) the requestor saved in the work round
                //    after their latest denial (if any)
                //    and within the work-save horizon; and
                // b) have since been saved in the review round.
                if ( $work_time > $min_timestamp &&
                    array_key_exists($state, $good_states) )
                {
                    @$counts_for_this_project[$work_user] += 1;
                }
            }
        }
        foreach ( $counts_for_this_project as $work_user => $n_good_pages )
        {
            $info =& $requestor_info[$work_user];
            $info[0] += $n_good_pages;
            $info[1][$projectid] = $n_good_pages;
        }
    }

    $t_after = $stopwatch->read();
    $t_diff = $t_after - $t_before;
    echo "time spent looking at projects: $t_diff\n";
    echo "average per project: ", $t_diff/count($projectids), "\n";

    echo "\n";
    echo "---------------------------------------\n";
    echo "With respect to the ", count($requestors), " requests for $review_round_id access:\n";
    echo "We show the number of pages saved-as-done in $work_round_id by the given user\n";
    echo "(since that user's latest denial of $review_round_id access, if applicable,\n";
    echo "and within the last $max_days_ago_for_work_save days)\n";
    echo "that have since been saved-as-done in $review_round_id.\n";

    echo "\n";
    arsort($requestor_info);
    foreach ( $requestor_info as $requestor => $info )
    {
        echo $info[0], " ", $requestor, "\n";

        arsort($info[1]);
        foreach ( $info[1] as $projectid => $n_good_pages )
        {
            $esc_username = urlencode($requestor);
            $query_string = "project=$projectid&select_by_user=$esc_username";
            $url = "$code_url/tools/project_manager/page_detail.php?$query_string";
            echo sprintf( "    %4d %s\n", $n_good_pages, $url );
        }
        echo "\n";
    }
    echo "-- \n";
    echo "This output was generated by the count_subsequents_for_access_requestors.php script.\n";
}

// vim: sw=4 ts=4 expandtab
?>
