<?
// Configurable stuff here:

// Added to the title: 
// (Remember to include a space before it if you want a (label))
$retread_tag = " (P2alt)";

// Added to the top of the project comments: (be sure to wrap it in <p></p>)
// This is a series of concatenated strings to avoid newlines complicating
// future bulk updates of the comments
$retread_comments = '[template=p2al.txt]';

// Special day queue to add these projects to 
// (will overwrite existing special day iff set)
// leave empty() if no special day wanted.
// Setting it to '[REMOVE]' will remove any special day set

$retread_special = '[REMOVE]';

// boolean
$make_easy_projects_average = true;

// The defaults for the rest of these are probably fine

//Project must be SQL IN this to be redone
$allowed_states = "('P2.proj_unavail','P2.proj_waiting')";

$new_state = "P1.proj_waiting";

// Page states
    // SQL IN
$allowed_page_states = "('P2.page_avail','P1.page_saved')";

$new_page_state = "P1.page_avail";

$dry_run = false;

// End configurable bits

$relPath = '../c/pinc/';
include_once($relPath . 'dp_main.inc');
include_once($relPath . 'site_vars.php');
include_once($relPath . 'dpsql.inc');
include_once($relPath . 'connect.inc');
include_once($relPath . 'user_is.inc');

if (!user_is_a_sitemanager())
    die("You're not allowed to do this.");

$retread_tag_d = htmlspecialchars($retread_tag);
$retread_comments_d = htmlspecialchars($retread_comments);

$retread_tag = mysql_real_escape_string($retread_tag);
$retread_comments = mysql_real_escape_string($retread_comments);

$stage = $_REQUEST['stage'];


echo "<html>
<head>
<title>Samsara</title>
<style type='text/css'>
.good, .good td {background: #6f6;}
.bad, .bad td {background: #f66;</style></head><body>
<h1>Return projects to P1 from pre-P2</h1>";


if (empty($stage) || $stage == 'enter')
{
    echo <<<EOS
    <form method='post' action='?stage=check'>
    <fieldset>
    <legend>Choose projects to repeat P1</legend>
    <textarea name='project_list' id='project_list' cols='50' rows='10'>
    </textarea><br />
    (Enter projectIDs, separated by spaces or newlines or commas or whatever. Guff <i>after</i> the projectid will be wiped out)<br /><br />
    <input type='submit' value='check projects' />
    </fieldset>
    </form>

    <fieldset>
    <legend>Retread settings (edit script to change)</legend>
    Tag added to title: <pre>$retread_tag_d</pre>
    <br /><br />
    Text added to the top of project comments: <pre>$retread_comments_d</pre>
    <br /><br />
    Special Day CODE (none if blank): <pre>$retread_special</pre>

    </fieldset>
EOS;
}
elseif ($stage == 'check')
{
    $projects = preg_split('/[\s,;]+/',$_POST['project_list']);
    
    foreach($projects as $key => $maybe_project)
    {
        if ( substr($maybe_project,0,9) !== 'projectID')
        {
            unset($projects[$key]);
            continue;
        }
        
        if ( strlen($maybe_project) != 22 )
        {
            $projects[$key] = substr($maybe_project,0,22);
        }
    }

    # re-key
    $projects = array_unique($projects);
    sort($projects);
    
    echo "<p>Checking ".count($projects)." projects for suitability...</p>";
    
    echo "<form method='post' action='?stage=doit'>";

    echo "<table border='1'><tr><th>do it to it</th><th>projectid</th><th>title</th><th>errors</th></tr>";

    foreach ($projects as $project)
    {
        $f_p_w_pid = "FROM projects WHERE projectid = '$project'";
        $errors = array();

        $result_pid = mysql_query("SELECT * FROM $project");
        if (!$result_pid)
        {
            $errors[] = "No projectid table (!)";
        }

        $result_ptable = mysql_query("SELECT * FROM projects WHERE projectid = '$project' LIMIT 1");
        $project_data = mysql_fetch_object($result_ptable);

        if (mysql_num_rows($result_ptable) == 0)
        {
            $errors[] = "No row in the projects table";
        }

        if (count($errors) == 0)
        {
            // It's not worth doing these checks if the project doesn't exist
            $result = mysql_query("SELECT state $f_p_w_pid AND state IN $allowed_states");

            if (mysql_num_rows($result) == 0)
            {
                $errors[] = "Project is in $project_data->state state, NOT IN $allowed_states.";
            }

            $result = mysql_query("SELECT * FROM $project WHERE state NOT IN $allowed_page_states LIMIT 1");
            if (mysql_num_rows($result) != 0)
            {
                $errors[] = "Some pages in project are not IN $allowed_page_states. Check Project page for details.";
            }
        }


        if ( count($errors) != 0 )
            echo "<tr class='bad'>
			 <td><input type='checkbox' name='projects_to_process[]' value='$project' /></td>";
        else
            echo "<tr class='good'>
			<td><input type='checkbox' name='projects_to_process[]' checked='checked' value='$project' /></td>";

        echo "<td>$project</td><td>$project_data->nameofwork</td><td>";

        foreach ($errors as $error)
        {
            echo $error.'<br />';
        }

        echo "</td></tr>";
    }

    echo "</table><p><big><b>Warning! Ticking a project that has
	    failed the suitablity check (red background) and pressing 
		'Do it' is probably a Very Bad Idea unless you're sure you 
		want to do that.</b></big></p>";

    echo "<input type='submit' value='Do it!' /></form>";


}
elseif ($stage == 'doit')
{
    $projects_to_retread = $_POST['projects_to_process'];

	$retreaded = 0;

    if (count($projects_to_retread) == 0)
       die("Error: no projects selected.");
       
    foreach ($projects_to_retread as $projectid)
    {
        list($retread_query, $pages_query) = get_queries_for($projectid);

        if ($dry_run)
        {
            echo $retread_query; $retread_result = true;
            echo $pages_query; $pages_result = true;
        }
        else
        {
            $retread_result = mysql_query($retread_query);
            if (!$retread_result) echo "Error retreading $projectid: ".mysql_error();
            $pages_result = mysql_query($pages_query);
            if (!$pages_result) echo "(Pages) error retreading $projectid: ".mysql_error();
		}

        if ($retread_result && $pages_result)
        {
            $retreaded++;
            echo "<p>$retreaded: Put $projectid back in P1.</p>";
        }

    }
}

else
{
    die("Bad 'stage'");
}


function get_queries_for($projectid)
{
    global $retread_tag,$retread_comments,$new_state,$new_page_state, 
	    $retread_special,$make_easy_projects_average;

    $comments = sprintf($retread_comments,$projectid);

    $query1 = "UPDATE `projects`
	SET `state` = '$new_state',
        `n_available_pages` = `n_pages`,
		`nameofwork` = concat(nameofwork, '$retread_tag'),
		`comments` = concat('$comments', comments) ";

    if (!empty($retread_special))
    {
        if($retread_special == '[REMOVE]')
        {
            $query1 .= ", `special_code` = ''";
        }
        else
        {
	        $query1 .= ", `special_code` = '$retread_special'";
        }
    }
        
    if ($make_easy_projects_average)
    {
        $difficulty = mysql_result(
		    mysql_query("SELECT difficulty
			    FROM projects 
				WHERE projectid = '$projectid'"),
			0,'difficulty');

		if ($difficulty == 'easy')
		    $query1 .= ", `difficulty` = 'average'";
    }




	$query1 .=	" WHERE `projectid` = '$projectid' LIMIT 1";

	$query2 = "UPDATE `$projectid`
	SET master_text = round1_text,
        round1_text = '',
        round1_user = '',
        round1_time = 0,
        state = '$new_page_state'";

    return array($query1,$query2);
}
