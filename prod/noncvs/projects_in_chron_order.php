<?PHP

// 2008-03-22:
// puppernutter is researching amastronardi's projects,
// and needs to go through them in chronological order by creation date,
// but Project Search doesn't provide a way to sort by creation date.
// Hence this script.

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) die("permission denied");

$q = "
    SELECT projectid, nameofwork
    FROM projects
    WHERE username = 'amastronardi'
    ORDER BY projectid
";

// dpsql_dump_query($q);
echo "<ol>\n";
$res = mysql_query($q) or die(mysql_error());
while ( list($projectid,$nameofwork) = mysql_fetch_row($res) )
{
    echo "<li>";
    echo "<a href='$code_url/project.php?id=$projectid&amp;detail_level=3'>$nameofwork</a>";
    echo "</li>";
    echo "\n";
}
echo "</ol>\n";

// vim: sw=4 ts=4 expandtab
?>
