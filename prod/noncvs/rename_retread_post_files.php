<?PHP

// This script allows you to 

$relPath='../c/pinc/';
include_once($relPath.'dpsql.inc');
if (0)
{
    include_once('cli.inc');
    include_once($relPath.'project_states.inc');
    include_once($relPath.'site_vars.php');
    include_once($relPath.'misc.inc'); // str_contains
    include_once($relPath.'connect.inc');
    new dbConnect;
}
else
{
    include_once($relPath.'dp_main.inc');

    if (!user_is_a_sitemanager()) die("permission denied");
}

$res = dpsql_query("
    SELECT projectid, nameofwork
    FROM projects
    WHERE NOT ".SQL_CONDITION_SILVER." AND NOT ".SQL_CONDITION_GOLD." AND state != '".PROJ_DELETE."'
    ORDER BY ".sql_collater_for_project_state('state')."
");
echo "examining ", mysql_num_rows($res), " projects...<br>\n";
$n_found = 0;
while ( list($projectid,$nameofwork) = mysql_fetch_row($res) )
{
    $plain_path = "$projects_dir/$projectid/{$projectid}.txt";
    $tei_path   = "$projects_dir/$projectid/{$projectid}_TEI.txt";

    $has_post_file = file_exists($plain_path) || file_exists($tei_path);
    $has_R = str_contains($nameofwork,'(R)');

    $s_has_post_file = ( $has_post_file ? 'has_file' : 'no_file' );
    $s_has_R = ( $has_R ? 'has_R' : 'no_R' );
    // if ( $has_post_file != $has_R )
    // if ( !$has_post_file && $has_R )
    // if ( $has_post_file && $has_R )
    if ( $has_post_file && !$has_R )
    {
        // echo "$projectid $s_has_post_file $s_has_R\n";
        echo "$s_has_post_file $s_has_R <a href='$code_url/project.php?id=$projectid&amp;detail_level=3'>$nameofwork</a><br>\n";
        $n_found += 1;
    }
}
echo "$n_found projects found<br>\n";

// vim: sw=4 ts=4 expandtab
?>
