<?PHP

// 2008-03-25: For piggy's CiP analysis.

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'misc.inc');
new dbConnect;

if ( $argc <= 1 )
    die( "usage: php -f extract_page_events_for_projects.php <filename>\n");

$filename = $argv[1];

$projectids = explode("\n",file_get_contents($filename));
$n = count($projectids);
$i = 0;
foreach ( $projectids as $projectid )
{
    $i++;
    fwrite(STDERR, sprintf("%4d/%4d: %s\n", $i, $n, $projectid ) );
    if ($projectid == '') continue;
    echo "\n";

    if ( !startswith($projectid,'projectID') ) { $projectid = 'projectID' . $projectid; }

    $sql = "
        SELECT event_id, timestamp, projectid, image, round_id, event_type from page_events
        WHERE projectid='$projectid'
        ORDER BY event_id
    ";
    // echo "$sql\n";
    $res = mysql_query($sql) or die(mysql_error());
    while ( $row = mysql_fetch_row($res) )
    {
        if ( is_null($row[4]) ) $row[4] = '--';
        echo implode(' ', $row), "\n";
    }
    usleep(1000000 * 0.5);
}

// vim: sw=4 ts=4 expandtab
?>
