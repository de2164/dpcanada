<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'project_events.inc');

if (!user_is_a_sitemanager() && 
    $pguser != 'PM QC' &&
    $pguser != 'stygiana') die("You are not allowed to run this script");

error_reporting(E_ALL);

echo "<pre>";

// get the projectid.
// check that it's a QC project, 
//   and that it's in F1 waiting or available or unavailable
// move it into F2 unavailable
// add stuff to the end of the project comments.
// copy the project.info file into the project directory, 
//   changing its name as you go

echo "<h2>Deal with a quality control project post P3</h2>";
echo "\n";

// see what we have...
$projectid      = array_get( $_POST, 'projectid',  NULL );
$submit_button  = array_get( $_POST, 'submit_button', '' );
$description    = stripslashes(array_get( $_POST, 'description', '' ));
$upload_dir     = array_get( $_POST, 'upload_dir', '' );
switch ( $submit_button )
{
    case '':
        // we are not here as a result of submitting the form. Display it.
        display_form($projectid, $description, $upload_dir, FALSE);
        break;

   case 'Check':
        // we are here as a result of submitting the form. 
        // we want to check that everything's OK, and give the user the
        // option to bail
        do_check($projectid, $description, $upload_dir);
        display_form($projectid, $description, $upload_dir, TRUE);
        break;

    case 'Do it!':
        // we are here as a result of submitting the form.
        // we want to go ahead and actually do the stuff.
        // do the checks again, just in case
        do_check($projectid, $description, $upload_dir);
        do_stuff($projectid, $description, $upload_dir);
        $url = "$code_url/project.php?id={$projectid}&amp;detail_level=3";
        echo "\n\n<a href='$url'>Project page</a>\n";
       break;

    default:
        // we're not meant to get here. What on earth is going on?
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function display_form($projectid, $description, $upload_dir, $checking)
{

    echo "<form method='post'>";
    if (!$checking) {
        // need to get user input
        echo "\nid of the QC project that's finished P3:                    <input type='text' name='projectid' size='35' ></input>\n";
        echo "\ndirectory in dpscans/QCprojects with the project info file: <input type='text' name='upload_dir' size='35' />\n";
        echo "\nDescription of the project (to go into the PCs):\n            <textarea name='description' rows='5' cols='50' ></textarea>\n";
        echo "<input type='submit' name='submit_button' value='Check'>";
    }
    else
    {
        echo "\n\nIf this information isn't correct, use your back button to correct it.\n";

        // just need to get the user's OK to go ahead
        $descr = htmlspecialchars($description, ENT_QUOTES);
        echo "<input type='hidden' name='projectid' value='$projectid'>";
        echo "<input type='hidden' name='upload_dir' value='$upload_dir'>";
        echo "<input type='hidden' name='description' value='$descr'>";
        echo "<input type='submit' name='submit_button'      value='Do it!'>";
    }
    echo "</form>";
}

// project exists, is a QC project, and is somewhere in F1.
// upload dir exists, and contains project info file
function do_check($projectid, $description, $upload_dir)
{
    global $uploads_dir;

    if ( is_null($projectid) || 
         "" == $projectid)
    {
        die( "Error: must supply projectid" );
    }

    // die if there's no project table for the project
    $res= mysql_query("
            DESCRIBE $projectid
        ") or die(mysql_error());

    // die if there's anything other than a single row in the projects table
    $res = mysql_query("
            SELECT nameofwork, username, state
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());

    $n_projects = mysql_num_rows($res);
    if ( $n_projects == 0 )
    {
        die( "projects table has no match for projectid='$projectid'" );
    }
    else if ( $n_projects > 1 )
    {
        die( "projects table has $n_projects matches for projectid='$projectid'. (Can't happen)" );
    }
 
    // OK, the project exists
    list($title, $pm, $current_state) = mysql_fetch_row($res);
    echo "   projectid : $projectid\n";
    echo "   title     : $title\n";
    echo "   PM        : $pm\n";
    echo "   state     : $current_state\n";

    // is it a QC project?
    $qc_ok = str_contains($title, '{Quality control}') &&  ($pm == 'PM QC');
    if (!$qc_ok) {
        die("The project must be a quality control project");
    }
    // now see if we're happy with the state it's in
    $state_ok =  "F1.proj_waiting" == $current_state || 
        "F1.proj_avail" == $current_state || 
        "F1.proj_unavail" == $current_state; 

    if (!$state_ok) {
        die("Error: project must be in F1"); 
    }

    // does the upload dir exist?
    // does the project info file exist?
    $project_info_file = "$uploads_dir/QCprojects/$upload_dir/project.info";
    echo "   info file : $project_info_file\n";

    if (! file_exists($project_info_file)) {
        die("Error: $project_info_file must exist");
    }
    echo "   description :</pre><p>";
    echo $description;
    echo "</p><pre>\n";

    // we're OK to go ahead
    echo ("\n    The project will be moved into F1 unavailable.
    Its project info file will be moved into the project directory,
    and information added to its project comments.");

}

// put the project into F1 unavailable,
// move the project info file,
// and add stuff to the project comments
function do_stuff($projectid, $description, $upload_dir)
{
    global $uploads_dir, $projects_dir, $projects_url, $pguser;
    $res = mysql_query("
            SELECT state
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());
    list($old_state) = mysql_fetch_row($res);
    $new_state = 'F1.proj_unavail';
    if ($old_state != $new_state)
    {
        $mdate = time();
        $query = "
    UPDATE projects SET state = '$new_state',
                        modifieddate = '$mdate'
                    WHERE projectid = '$projectid';";
        $res = mysql_query($query) or die(mysql_error());
        log_project_event( $projectid, $pguser, 'transition', $old_state,
                           $new_state, 'QC project finished P3');
    }

    $project_info_file = "$uploads_dir/QCprojects/$upload_dir/project.info";
    $new_info_file = "$projects_dir/$projectid/project_info.txt";
    if (copy($project_info_file, $new_info_file)) {
        echo "\n copied $project_info_file to $new_info_file";
    }
    else {
        die("Couldn't copy $project_info_file to $new_info_file");
    }

    echo "\n   updating project comments";
    $add_text = "\n<p>$description</p>";
    $add_text .= "\n<p>Information about the source of the pages in this project is now available in <a href='$projects_url/$projectid/project_info.txt'>project_info.txt</a>. The first few lines give information about how the pages were selected. After that, there is one line per page, giving the png number in this project, the source project, and the png in the source project.</p>";
    $add_text = addslashes($add_text);
    $query = "UPDATE projects SET comments = CONCAT(comments, '$add_text') 
              WHERE projectid = '$projectid' ";
    $res = mysql_query($query) or die(mysql_error());

}
// vim: sw=4 ts=4 expandtab
?> 
