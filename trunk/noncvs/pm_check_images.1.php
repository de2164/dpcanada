<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc'); // user_is_PM
include_once($relPath.'site_vars.php'); // $projects_dir

if ( !user_is_PM() )
{
	die("You are not listed as a PM.");
}

echo "<h2>Project Image Validation for PM: $pguser</h2>";

chdir($projects_dir);

$res = mysql_query("
    SELECT projectid, nameofwork, state
    FROM projects
    WHERE username = '$pguser'
        AND archived = 0
        AND state != 'project_delete'
    ORDER BY projectid
") or die(mysql_error());

if ( mysql_num_rows($res) == 0 )
{
    die("You are a PM, but you don't have any projects that aren't archived or deleted.");
}

echo "
<p>
For each project, a file was generated in the project directory
which contains the output of the programs used to check the image files.
If no image files appear after the PNGS: or JPGS: section of the output,
the image files for the project should be fine.
However, if there are image file names in the output,
those images are corrupted and need to be replaced.
As PM, you can use the 'Fix' link on the projects page details page
to correct page images yourself.
Use the normal db-req procedure to have any corrupted illustration images replaced.
If a project has not yet been checked, a message stating so will appear,
and you should check this page again later.
</p>
";

echo "<pre>\n";

while ( list($projectid, $nameofwork, $state) = mysql_fetch_row($res) )
{
    echo "\n";
    echo "<b><a href='$code_url/project.php?id=$projectid&amp;detail_level=4'>$nameofwork</a></b> ($state)\n";
    $filename = "$projectid/image_errors.txt";
    if ( is_file($filename) )
    {
        readfile($filename);
    }
    else
    {
        echo "$projectid has not been checked yet.\n";
    }
}

echo "</pre>\n";

// vim: sw=4 ts=4 expandtab
?>
