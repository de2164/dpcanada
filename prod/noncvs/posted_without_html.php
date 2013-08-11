<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'pg.inc');

$title = _('Projects posted without HTML');

echo "<h3>$title</h3>";

$condition = SQL_CONDITION_GOLD;
$res = mysql_query("
    SELECT projectid, nameofwork, postednum, formats
    FROM projects
        LEFT OUTER JOIN pg_books
        ON projects.postednum=pg_books.etext_number
    WHERE $condition
        AND NOT INSTR(formats, 'HTML')
    ORDER BY postednum
");

echo "<table border='1'>\n";
{
    echo "<tr>\n";
    echo "<th>Title</th>\n";
    echo "<th>PG etext #</th>\n";
    echo "<th>Formats</th>\n";
    echo "</tr>\n";
}
while( list($projectid,$nameofwork,$postednum,$formats) = mysql_fetch_row($res) )
{
    echo "<tr>\n";

    echo "<td><a href='$code_url/project.php?id=$projectid'>$nameofwork</a></td>\n";

    echo "<td nowrap>";
    echo get_pg_catalog_link_for_etext($postednum,$postednum);
    echo "</td>\n";

    echo "<td nowrap>$formats</td>\n";

    echo "</tr>\n";
}
echo "</table>\n";

// vim: sw=4 ts=4 expandtab
?>
