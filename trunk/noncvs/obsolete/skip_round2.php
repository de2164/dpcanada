<?PHP

// Guts and brains by jmdyck; hack to skip F2 added by mike (very inelegant
// won't work if F2 is not the last round) ; also added confirm page

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'DPage.inc'); // Pages_prepForRound
include_once($relPath.'f_move_post.inc');
include_once($relPath.'../tools/project_manager/post_files.inc');

if (!user_is_a_sitemanager()) die("permission denied");

error_reporting(E_ALL);

$projectid = @trim($_GET['projectid']);

if (empty($projectid))
{
    echo "
        <form mode='get'>
        projectid: <input type='text' name='projectid'>
        <input type='submit'>
        </form>
    ";
}
else
{
    echo "<pre>\n";
    echo "<a href='$code_url/project.php?id=$projectid&amp;detail_level=3'>$projectid</a>\n";

    $project = new Project($projectid);

    echo "($project->nameofwork)\n";
    echo "in '$project->state'\n";
    echo "PM '$project->username'\n";
    echo "PPer '<a href='$site_url/noncvs/user_pp.php?username=$project->checkedoutby'>$project->checkedoutby</a>'\n";

    $round = get_Round_for_project_state( $project->state );

    if ( is_null($round) || 
        !in_array($project->state,
             array(
                 $round->project_unavailable_state,
                 $round->project_waiting_state
             )  
         )
)
    {
        die("For this action, the project must be in a round's waiting state (or unavailable), but it is in '$project->state'.");
    }

    // Check on pages
    $res = mysql_query("
        SELECT COUNT(*)
        FROM $projectid
        WHERE state != '$round->page_avail_state'
    ") or die(mysql_error());
    $count = mysql_result($res,0);
    if ( $count > 0 )
    {
        die("For this action, the project's pages must all be in the available state, but $count are not.");
    }

    // ------------------------------------

    echo "The project appears to qualify for this action...\n";

    // next round
    $destination_round = get_round_for_round_number( $round->round_number+1 );

    if ( is_null($destination_round) )
    {
        echo "F2 will be skipped.";
        $skip_F2 = true;
    }

    if (!isset($_REQUEST['confirmed']))
    {
        show_confirm_button();
        die;
    }

    if ($skip_F2)
    {
        do_f2_skip($projectid);
        die;
    }

    echo "$round->id to $destination_round->id\n";

    echo "Copying $round->prevtext_column_name to $round->text_column_name...\n";
    mysql_query("
        UPDATE $projectid
        SET
            $round->time_column_name = UNIX_TIMESTAMP(),
            $round->user_column_name = '',
            $round->text_column_name = $round->prevtext_column_name
    ") or die(mysql_error());

    // AVOIDING project_transition() FOR NOW.
    // (because it doesn't see this as a legal transition yet)

    $settings = "state='$destination_round->project_waiting_state'";
    $settings .= ", modifieddate=UNIX_TIMESTAMP()";
    $settings .= ", nameofwork=CONCAT(nameofwork, ' [$round->id skipped]')";

    echo "settings: $settings\n";

    echo "Project state transition ...\n";
    mysql_query("
        UPDATE projects SET $settings WHERE projectid = '$projectid'
    ") or die(mysql_error());

    // ------------

    echo "Page state transition...\n";
    Pages_prepForRound( $projectid, $destination_round->round_number );

    move_project_thread(
        $projectid,
        get_forum_id_for_project_state($destination_round->project_waiting_state)
    );

    echo "success?\n";

    echo "<a href='$code_url/project.php?id=$projectid&amp;detail_level=3'>Go to Project Home</a>\n";
}

function show_confirm_button()
{
    global $projectid;
    echo "</pre><form method='post'> 
        <input type='hidden' name='projectid' value='$projectid' />
        <input type='hidden' name='confirmed' value='ja' />
        <input type='submit' value='Do it' />
        </form>";
}

function do_f2_skip($pid)
{
    global $code_url;
    $result = dpsql_query("
            UPDATE projects 
                SET
                nameofwork = CONCAT(nameofwork, ' [F2 Skipped]'),
                state = 'proj_post_first_checked_out',
                n_available_pages = 0
            WHERE projectid = '$pid'");
    generate_post_files($pid,'F1');

    $projects_path = '/data/htdocs/projects';

    $to_exec="chown dpadmin:dpadmin $projects_path/$pid/proj*";

    echo "\nrunning `$to_exec`...";

    echo shell_exec($to_exec);

    if ($result)
    {
    echo "\nSuccess? <a href='$code_url/project.php?id=$pid'>Go to project home</a>";
    }
    else
    {
        echo "Problem. See: <a href='$code_url/project.php?id=$pid'>project home</a>";
    }
}


// vim: sw=4 ts=4 expandtab
?>
