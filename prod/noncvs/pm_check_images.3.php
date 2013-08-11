<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc'); // user_is_PM
include_once($relPath.'site_vars.php'); // $projects_dir
include_once($relPath.'misc.inc'); // file_get_contents()

if ( !user_is_PM() )
{
	die("You are not listed as a PM.");
}

if ( user_is_proj_facilitator() )
{
    // Check for 'username' param.
    $username = array_get( $_GET, 'username', $pguser );
}
else
{
    $username = $pguser;
}


echo "<h2>Project Image Validation for PM: $username</h2>";

chdir($projects_dir);

$res = mysql_query("
    SELECT projectid, nameofwork, state
    FROM projects
    WHERE username = '$username'
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
For each project, we have generated a file
that contains the output of two programs used to check image files for corruption.
This file appears as 'image_errors.txt'
under 'Extra Files in Project Directory'
on the project page.
If no image files appear after the PNGS: or JPGS: section of this file,
the image files for the project should be fine.
However, if there are image file names in the output,
those images are corrupted and need to be replaced.
</p>

<p>
For the following listing,
we have gone to every (non-archived, non-deleted) project
of which you are the PM,
and examined its 'image_errors.txt' file.
<b>If the file indicates no corrupted image files,
the project does not appear here.</b>
Otherwise, the image_errors.txt file's contents are included in this listing.
(If a project has not yet been checked,
a message stating so will appear,
and you should check this page again later.)
</p>

<p>
As PM, you can use the 'Fix' link on the projects page details page
to correct page images yourself.
Use the normal db-req procedure to have any corrupted illustration images replaced.
</p>
";

echo "<pre>\n";

while ( list($projectid, $nameofwork, $state) = mysql_fetch_row($res) )
{
    $header = "\n<b><a href='$code_url/project.php?id=$projectid&amp;detail_level=4'>$nameofwork</a></b> ($state)\n";

    $filename = "$projectid/image_errors.txt";
    if ( !is_file($filename) )
    {
        echo $header;
        echo "$projectid has not been checked yet.\n";
        continue;
    }

    $c = file_get_contents($filename);
    if ( $c == "Project $projectid:\nPNGS:\npngcheck completed, RV: 0\nJPGS:\njpeginfo completed, RV: 1\n" )
    {
        // echo $header;
        // echo "no corrupt page images\n";
        continue;
    }

    echo $header;
    echo $c;
}

echo "</pre>\n";

// vim: sw=4 ts=4 expandtab
?>
