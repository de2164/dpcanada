<?PHP
$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$res = mysql_query("
    SELECT projectid, comments
    FROM projects
    WHERE INSTR(comments,'href')
") or die(mysql_error());
while( list($projectid, $comments) = mysql_fetch_row($res) )
{
    $n = preg_match_all('/href\s*=\s*("[^"]*"|\'[^\']*\')/', $comments, $matches, PREG_PATTERN_ORDER );
    if ( $n === FALSE )
    {
        echo "an error occurred";
    }
    else
    {
        foreach( $matches[1] as $attval )
        {
            $attval = addcslashes(substr($attval,1,-1), "\0..\37");
            // echo "$projectid $attval\n";
            echo "$attval\n";
        }
        
    }
}

// vim: sw=4 ts=4 expandtab
?>
