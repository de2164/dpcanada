<?PHP

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$res = mysql_query("
    SELECT projectid
    FROM projects
    WHERE archived=1
    ORDER BY projectid
") or die(mysql_error());
while( list($projectid) = mysql_fetch_row($res) )
{
    $res2 = mysql_query("
        SELECT COUNT(*)
        FROM page_events
        WHERE projectid='$projectid'
    ") or die(mysql_error());
    list($n_page_events) = mysql_fetch_row($res2);
    echo "$projectid $n_page_events\n";
}

// vim: sw=4 ts=4 expandtab
?>
