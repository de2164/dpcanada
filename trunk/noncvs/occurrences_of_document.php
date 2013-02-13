<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) die("permission denied");

echo "<h4>Projects (other than PG-posted projects) whose comments reference 'document.php'</h4>\n";

$res = mysql_query("
    SELECT
        projectid,
        nameofwork,
        REPLACE(
            SUBSTRING(comments,GREATEST(1,INSTR(comments,'document.php')-50),156),
            '\r\n',
            '|')
    FROM projects
    WHERE state != 'proj_submit_pgposted'
        AND INSTR(comments,'document.php')
        -- and not instr(comments,'formatting guidelines')
    ORDER BY SUBSTRING(comments, INSTR(comments,'document.php'))
") or die(mysql_error());

echo "<table border='1'>";
while ( list($projectid,$nameofwork,$comments_excerpt) = mysql_fetch_row($res) )
{
    echo "<tr>";
    echo "<td>";
    echo "<a href='$code_url/project.php?id=$projectid'>$nameofwork</a>";
    echo "</td>";
    echo "<td>";
    echo htmlspecialchars($comments_excerpt);
    echo "</td>";
    echo "</tr>\n";
}
echo "</table>";

// vim: sw=4 ts=4 expandtab
?>
