<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');

if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer())) die("permission denied");

$title = _('Pending Requests for Access');

$no_stats=1;
theme($title,'header');

echo "<h1>$title</h1>\n";

foreach ( $Stage_for_id_ as $stage )
{
    if ( $stage->after_satisfying_minima == 'REQ-HUMAN' )
    {
        $activity_ids[] = $stage->id;
    }
}

$activity_ids[] = 'P2_mentor';

// Look for unexpected activity_ids
$res = mysql_query("
    SELECT DISTINCT REPLACE(setting,'.access', '')
    FROM usersettings
    WHERE setting LIKE '%.access' AND value='requested'
") or die(mysql_error());
while ( list($activity_id) = mysql_fetch_row($res) )
{
    if ( !in_array( $activity_id, $activity_ids ) )
    {
        $activity_ids[] = $activity_id;
    }
}

// ----------------------------------

mysql_query("
    CREATE TEMPORARY TABLE access_log_summary
    SELECT 
        activity,
        subject_username,
        MAX( timestamp * (action='request'         ) ) AS t_latest_request,
        MAX( timestamp * (action='deny_request_for') ) AS t_latest_deny
    FROM access_log
    GROUP BY activity, subject_username
") or die(mysql_error());

mysql_query("ALTER TABLE access_log_summary ADD INDEX su_activity (subject_username, activity)");

foreach ( $activity_ids as $activity_id )
{
    echo "<h3>";
    echo sprintf( _('Users requesting access to %s'), $activity_id );
    echo "</h3>\n";

    $access_name = "$activity_id.access";

    $res = mysql_query("
        SELECT
            usersettings.username,
            users.u_id,
            access_log_summary.t_latest_request,
            access_log_summary.t_latest_deny,
            users.t_last_activity
        FROM usersettings
            LEFT OUTER JOIN users USING (username)
            LEFT OUTER JOIN access_log_summary ON (
                access_log_summary.subject_username = usersettings.username
                AND
                access_log_summary.activity = '$activity_id'
            )
        WHERE setting = '$access_name' AND value='requested'
        ORDER BY username
    ") or die(mysql_error());

    if ( mysql_num_rows($res) == 0 )
    {
        $word = _('none');
        echo "($word)";
    }
    else
    {
        $review_round = get_Round_for_round_id($activity_id);
        if ( $review_round && $review_round->after_satisfying_minima == 'REQ-HUMAN' )
        {
            $can_review_work = TRUE;
            // These users are all requesting access to round Y.  For each, we will
            // provide a link to allow the requestor to review their round X work,
            // by considering each page they worked on in X, and comparing
            // their X result to the subsequent Y result (if it exists yet).
            //
            // (We assume that X is the round immediately preceding Y.)
            $work_round = get_Round_for_round_number($review_round->round_number-1);

            $round_params = "work_round_id={$work_round->id}&amp;review_round_id={$review_round->id}";
        }
        else
        {
            $can_review_work = FALSE;
        }

        echo "<table border='1'>\n";

        {
            echo "<tr>";
            echo "<th>username (link to member stats)</th>";
            if ( $can_review_work )
            {
                echo "<th>link to review work</th>";
            }
            echo "<th>this request</th>";
            echo "<th>prev denial</th>";
            echo "<th>last on site</th>";
            echo "</tr>";
            echo "\n";
        }
        $seconds = 60 * 60 * 24;
        $now = time();
        $tformat = '%Y-%m-%d';
        while ( list($username, $u_id, $t_latest_request, $t_latest_deny, $t_last_on_site) = mysql_fetch_row($res) )
        {
            $member_stats_url = "$code_url/stats/members/mdetail.php?id=$u_id";
            $t_latest_request_f = strftime($tformat, $t_latest_request);
            $t_latest_request_d = round(($now - $t_latest_request) / $seconds);
            $t_latest_deny_f = '';
            $t_latest_deny_d = -1;
            if ($t_latest_deny != 0) 
            {
                $t_latest_deny_f = strftime($tformat, $t_latest_deny);
                $t_latest_deny_d = round(($now - $t_latest_deny) / $seconds);
            }
            $t_last_on_site_f = strftime($tformat, $t_last_on_site);
            $t_last_on_site_d = round(($now - $t_last_on_site) / $seconds);

            echo "<tr>";
            echo   "<td align='center'>";
            echo     "<a href='$member_stats_url'>$username</a>";
            echo   "</td>";
            if ( $can_review_work )
            {
// pull how many pages the user has had reviewed in the review round
// yup, its a hack job pulled from a test version of review_round.php
$total_pages_reviewed = 0;
$review_pages_limit = 100;

// find all projects they've worked on starting with the oldest
$res2 = mysql_query("
    SELECT projectid
    FROM user_project_info
    WHERE username='$username'
    ORDER BY t_latest_page_event
") or die("Aborting");
while ( list($projectid) = mysql_fetch_row($res2) )
{
    // see if it actually went through the review round (for perf reasons)
    $review_round_result = mysql_query("
        SELECT COUNT(*)  
        FROM project_events
        WHERE projectid='$projectid' and details2='{$review_round->project_available_state}'
       ");
    list($done_in_rround) = mysql_fetch_row($review_round_result);
    mysql_free_result($review_round_result);
    if (0 == $done_in_rround)
        continue;

    // see if the pages table exists
    $describe_results = mysql_query("SELECT 1 FROM $projectid LIMIT 0");
    if (!$describe_results)
        continue;
    mysql_free_result($describe_results);

    // count the pages that have been reviewed
    $query = "
        SELECT COUNT(*)
        FROM $projectid
        WHERE {$work_round->user_column_name}='$username' and {$review_round->user_column_name} <> ''";
    $res3 = mysql_query($query);
    list( $n_pages ) = mysql_fetch_row($res3);
    mysql_free_result($res3);

    // sum the number of pages
    $total_pages_reviewed += $n_pages;

    // stop if we hit our artificial limit for (perf reasons)
    if($total_pages_reviewed > $review_pages_limit) {
        $total_pages_reviewed = "&gt; $total_pages_reviewed";
        break;
    }
}
mysql_free_result($res2);
                $review_work_url = "review_work_instrumented3.php?username=$username&amp;$round_params";
                echo   "<td align='center'>";
                echo     "<a href='$review_work_url'>rw</a> ($total_pages_reviewed pages reviewed)";
                echo   "</td>";
            }
            echo   "<td align='left'>";
            echo     $t_latest_request_f;
            echo " ($t_latest_request_d&nbsp;days)";
            echo   "</td>";
            echo   "<td align='left'>";
            echo     $t_latest_deny_f;
            if ($t_latest_deny_d >= 0) {
                echo " ($t_latest_deny_d&nbsp;days)";
            }
            echo   "</td>";
            echo   "<td align='left'>";
            echo     $t_last_on_site_f;
            echo " ($t_last_on_site_d&nbsp;days)";
            echo   "</td>";
            echo "</tr>";
            echo "\n";
        }
        echo "</table>\n";
    }
}

echo '<br>';

theme('','footer');

// vim: sw=4 ts=4 expandtab
?>
