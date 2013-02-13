<?PHP

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'site_vars.php'); // $projects_dir
include_once($relPath.'misc.inc'); // file_get_contents()
include_once($relPath.'connect.inc');
new dbConnect;

echo "<h2>PMs with projects with corrupt images</h2>\n";

$sweep_end_time = filemtime("$projects_dir/projectID3c1bfeebdccac/image_errors.txt");
$sweep_end_time = strftime('%Y-%m-%d %H:%M', $sweep_end_time);
echo "<p>(According to the sweep that ended $sweep_end_time server time.)</p>\n";

chdir($projects_dir);

$res1 = mysql_query("
    SELECT DISTINCT username
    FROM projects
    WHERE 
        archived = 0
        AND state != 'project_delete'
    ORDER BY username
") or die(mysql_error());

$n_pms_to_check = mysql_num_rows($res1);

echo "<p>Checking $n_pms_to_check PMs with non-deleted non-archived projects...</p>\n";

$n_active_projects_total = 0;
$n_bad_projects_total = 0;
$n_pms_with_bad_projects = 0;
while (list($username) = mysql_fetch_row($res1) )
{
    stderr("\n", $username, "\n");

    $res2 = mysql_query("
        SELECT projectid
        FROM projects
        WHERE username = '$username'
            AND archived = 0
            AND state != 'project_delete'
        ORDER BY projectid
    ") or die(mysql_error());

    $n_active_projects_this_pm = mysql_num_rows($res2);
    assert($n_active_projects_this_pm) > 0;
    $n_active_projects_total += $n_active_projects_this_pm;

    stderr( "$username has $n_active_projects_this_pm active projects\n");

    $n_bad_projects_this_pm = 0;
    while ( list($projectid) = mysql_fetch_row($res2) )
    {
        $image_check_filename = "$projectid/image_errors.txt";
        if ( !is_file($image_check_filename) )
        {
            $n_bad_projects_this_pm += 1;
            continue;
        }

        $image_check_content = file_get_contents($image_check_filename);
        $success_pattern =
            "/^Project $projectid:\nPNGS:\npngcheck completed, RV: [02]\nJPGS:\njpeginfo completed, RV: 1\n$/";
        if ( preg_match( $success_pattern, $image_check_content ) )
        {
            // echo $header;
            // echo "no corrupt page images\n";
            continue;
        }

        $n_bad_projects_this_pm += 1;
    }
    stderr( "    of which $n_bad_projects_this_pm have corrupt images\n" );
    $n_bad_projects_total += $n_bad_projects_this_pm;

    if ( $n_bad_projects_this_pm > 0 )
    {
        echo "<p>$username has <a href='pm_check_images.php?username=$username'>$n_bad_projects_this_pm projects with corrupt images</a></p>\n";
        $n_pms_with_bad_projects += 1;
    }

    // if ($username == 'beautysmom') break;
}

echo "<p>Out of $n_pms_to_check PMs with $n_active_projects_total non-deleted non-archived projects,
$n_pms_with_bad_projects of them have a total of $n_bad_projects_total projects with corrupt images.</p>";

// vim: sw=4 ts=4 expandtab
?>
