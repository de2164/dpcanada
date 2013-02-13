<?PHP

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

foreach ( file("/tmp/projectids_in_pe") as $line )
{
    $projectid = rtrim($line);
    $res = mysql_query("DESCRIBE $projectid");
    if ( $res == FALSE && mysql_errno() == 1146 )
    {
        // project's page table doesn't exist.
        $res2 = mysql_query("
            SELECT nameofwork, state, deletion_reason
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());
        list($nameofwork,$state,$deletion_reason) = mysql_fetch_row($res2);
        echo "$projectid $state '$deletion_reason' $nameofwork\n";
    }
}

// vim: sw=4 ts=4 expandtab
?>
