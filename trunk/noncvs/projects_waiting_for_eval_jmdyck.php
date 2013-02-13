<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'Stopwatch.inc');

if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer())) die("permission denied");

$use_all_projects = FALSE;

// $use_all_projects = FALSE;
// P2/P3 491 projects  1
// 394.503091 for all queries; 57.50797 for the first query

// $use_all_projects = TRUE;
// P2/P3 508 projects  3
// 230.113217 for all queries; 0.021195 for the first query

// ---------------------

// F1/F2:
// $use_all_projects = TRUE;
// 180 projects  4
// 99.095506 for all queries; 0.011937 for the first query

// $use_all_projects = FALSE;
// 178 projects  2
// 70.304262 for all queries; 70.067304 for the first query

// $use_all_projects = FALSE;
// 180 projects
// 173.214477 for all queries; 39.243208 for the first query

// $use_all_projects = TRUE;
// 183 projects
// 124.502995 for all queries; 0.007876 for the first query

// $use_all_projects = TRUE;
// 183 projects
// 4.4089 for all queries; 0.009439 for the first query

// $use_all_projects = FALSE; query w JOINs
// 181 projects
// 23.914398 for all queries; 23.7557 for the first query

// $use_all_projects = FALSE; original query
// 181 projects waiting for F2
// 8.548427 for all queries; 5.861538 for the first query

// caching effects: they were run in the following order 1 3 4 2
// some rough calculations show that the time per project looked at
// for the second query is between .2 and .7 second. At .2 second
// it's better to be selective on the P rounds, and there's not much
// difference on the F rounds.
// More worrying is why the number of projects selected is different 
// for the two methods.
error_reporting(E_ALL);

echo "<pre>";


echo "<h2>Find projects with work waiting for review</h2>";
echo "\n";

$round_ids_ = array("P1", "P2", "P3", "F1", "F2");

// get the work round and review round

// see what we have...
$submit_button  = array_get( $_POST, 'submit_button', '' );
$work_round  = array_get( $_POST, 'work_round', '' );
$review_round  = array_get( $_POST, 'review_round', '' );

switch ( $submit_button )
{
    case '':
        // we are not here as a result of submitting the form. Display it.
        display_form($work_round, $review_round, $round_ids_);
        break;

    case 'Do it!':
        display_form($work_round, $review_round, $round_ids_);
        // we are here as a result of submitting the form.
        // we want to go ahead and actually do the stuff.
        do_stuff($work_round, $review_round);
       break;

    default:
        // we're not meant to get here. What on earth is going on?
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function display_form($work_round, $review_round, $round_ids_)
{

    echo "<form method='post'>";

    echo "\n\nWork round <select name='work_round'>";
    foreach ($round_ids_ as $rid)
    {
        $sel = "";
        if ($work_round == $rid) {
            $sel = " selected='selected'";
        }
        echo "\n                  <option value='$rid' $sel>$rid</option>";
    }
    echo "\n      </select>  ";

    echo "&nbsp;&nbsp;&nbsp;&nbsp;Review round <select name='review_round'>";
    foreach ($round_ids_ as $rid)
    {
        $sel = "";
        if ($review_round == $rid) {
            $sel = " selected='selected'";
        }
        echo "\n                  <option value='$rid' $sel>$rid</option>";
    }
    echo "\n      </select>  ";
    echo "\n\nE.g., for F2 access work round is F1 and review round is F2";
    echo "\n\n<input type='submit' name='submit_button' value='Do it!'>";

    echo "\n</form>";
}

// find the list of relevant projects that any evaluatee has worked on
// then for each project, list the evaluatees and the number of pages they've done
function do_stuff($work_round, $review_round)
{
    global $code_url, $use_all_projects;
 
    // query for relevant projects only

    $query = "
              SELECT projects.projectid, projects.nameofwork, projects.state 
              FROM usersettings, page_events, projects
              WHERE usersettings.setting = '$review_round.access' AND
                    usersettings.value = 'requested' AND
                    page_events.username = usersettings.username AND
                    page_events.round_id = '$work_round' AND
                    page_events.event_type = 'saveAsDone' AND
                    projects.projectid = page_events.projectid AND
                    projects.state IN ( '$review_round.proj_waiting', 'project_delete')
              GROUP BY projects.projectid
              ORDER BY projects.projectid
             ";

    /*
    $query = "
              SELECT projects.projectid, projects.nameofwork, projects.state 
              FROM
                  page_events
                  JOIN usersettings USING (username)
                  JOIN projects     USING (projectid)
              WHERE usersettings.setting = '$review_round.access' AND
                    usersettings.value = 'requested' AND
                    page_events.round_id = '$work_round' AND
                    page_events.event_type = 'saveAsDone' AND
                    projects.state = '$review_round.proj_waiting'
              GROUP BY projects.projectid
              ORDER BY projects.projectid
             ";
    */

    // query for all projects waiting for the round
    $query2 = "
              SELECT projects.projectid, projects.nameofwork, projects.state 
              FROM projects
              WHERE projects.state = '$review_round.proj_waiting'
              ORDER BY projectid
             ";
    $watch = new Stopwatch;
    $watch->start();
    if ($use_all_projects)
    {
        $result = mysql_query($query2) or die(mysql_error());
    }
    else
    {
        $result = mysql_query($query) or die(mysql_error());
    }
    $ttaken1 = $watch->read();

    echo "\n</pre>";
    // OK, we've got the projects

    echo "\n<table border='1'>";
    echo "\n<tr><th width='50%'>Project</th><th>Usernames</th><th>Users</th><th>Pages</th></tr>";
    $round = get_Round_for_round_id($work_round);
    $col = $round->user_column_name;

    // now get the info we want for each of them
    if ($use_all_projects)
    {
        $project_count = deal_with_projects2($result, $col, $review_round);
    }
    else
    {
        $project_count = deal_with_projects($result, $col, $review_round);
    }
    echo "\n</table>";
    echo "\n<p>$project_count projects waiting for $review_round</p>";
    $watch->stop();
    $ttaken2 = $watch->read();
    echo "\n<p>$ttaken2 for all queries; $ttaken1 for the first query</p>";
}

// use this when we selected only those projects we are interested in
function deal_with_projects($result, $col, $review_round)
{
    global $code_url;
    $project_count = mysql_num_rows($result);
	while ( list($projectid, $title, $state) = mysql_fetch_row($result) )
	{ 
        $query = "
                   SELECT $projectid.$col, COUNT(*)
                   FROM $projectid, usersettings
                   WHERE usersettings.setting = '$review_round.access' AND
                         usersettings.value = 'requested' AND
                         $projectid.$col = usersettings.username
                   GROUP BY $projectid.$col
                 ";
        $proj_result = mysql_query($query);
        if ( $proj_result === FALSE )
        {
            if ( mysql_errno() == 1146 )
            {
                // table does not exist, that's okay
                continue;
            }
            else
            {
                die(mysql_error());
            }
        }
        $url = "$code_url/project.php?id=$projectid&detail_level=3";
        echo "\n<tr><td><a href='$url'>$title</a></td>";
        $user_count = mysql_num_rows($proj_result);
        $tpages = 0;
        echo "\n<td>";
        while ( list($username, $pagecount) = mysql_fetch_row($proj_result) )
        { 
            echo "\n$username, $pagecount pages<br />";
            $tpages += $pagecount;
        }
        echo "\n</td>";
        echo "\n<td>$user_count</td><td>$tpages</td>";
        echo "\n</tr>";
    }
    return $project_count;
}
// use this when we selected all waiting projects
function deal_with_projects2($result, $col, $review_round)
{
    global $code_url;
    $project_count = 0;
	while ( list($projectid, $title, $state) = mysql_fetch_row($result) )
	{ 
        $url = "$code_url/project.php?id=$projectid&detail_level=3";
        $query = "
                   SELECT $projectid.$col, COUNT(*)
                   FROM $projectid, usersettings
                   WHERE usersettings.setting = '$review_round.access' AND
                         usersettings.value = 'requested' AND
                         $projectid.$col = usersettings.username
                   GROUP BY $projectid.$col
                 ";
        $proj_result = mysql_query($query) or die(mysql_error());
        $user_count = mysql_num_rows($proj_result);
        if ($user_count > 0)
        {
            $project_count ++;
            echo "\n<tr><td><a href='$url'>$title</a></td>";
            $tpages = 0;
            echo "\n<td>";
            while ( list($username, $pagecount) = mysql_fetch_row($proj_result) )
            { 
                echo "\n$username, $pagecount pages<br />";
                $tpages += $pagecount;
            }
            echo "\n</td>";
            echo "\n<td>$user_count</td><td>$tpages</td>";
            echo "\n</tr>";
        }
    }
    return $project_count;
}
// vim: sw=4 ts=4 expandtab
?> 
