<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

if (!user_is_a_sitemanager()) die("permission denied");

echo "<pre>\n";

echo "Deleted projects without a deletion_reason:\n";
echo "\n";

$res = dpsql_query("
    SELECT projectid, nameofwork
    FROM projects
    WHERE
        state = 'project_delete'
        AND
        deletion_reason = ''
        -- comments LIKE '%duplicate%'
        -- nameofwork LIKE '%needs fixing%'
    ORDER BY nameofwork
");
while ( list($projectid,$nameofwork) = mysql_fetch_row($res) )
{
    echo "<a href='$code_url/project.php?id=$projectid'>$nameofwork</a>\n";
}

echo "</pre>\n";

// vim: sw=4 ts=4 expandtab
?>
