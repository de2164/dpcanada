<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

echo "<h2>Cases where multiple projects with different values for the 'language' property are PG-posted under a single etext number</h2>\n";

$res = dpsql_query("
    SELECT postednum, COUNT(DISTINCT language) AS c
    FROM projects
    WHERE state='proj_submit_pgposted'
    GROUP BY postednum
    HAVING c > 1
    ORDER BY postednum
");

while ( list($postednum,$c) = mysql_fetch_row($res) )
{
    echo "<br>\n";
    echo "PG etext $postednum\n<br>";
    dpsql_dump_query("
        SELECT projectid, language, nameofwork
        FROM projects
        WHERE postednum='$postednum'
    ");
}

// vim: sw=4 ts=4 expandtab
?>
