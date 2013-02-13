<?PHP

$relPath='../../c/pinc/';
include_once($relPath.'dpsql.inc');
include_once('../cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$lccn_for_projectid = array();
while ( ($line = fgets(STDIN)) !== FALSE )
{
    list($projectid,$lccn) = explode('|',rtrim($line));
    if ( preg_match('/^([a-z]+ *)?\d{8,}$/', $lccn) )
    {
        // echo "good: $lccn\n";
    }
    else
    {
        // echo "bad: $lccn\n";
        $lccn = 'invalid';
    }
    if ( array_key_exists($projectid, $lccn_for_projectid) )
    {
        if ( $lccn != $lccn_for_projectid[$projectid] )
        {
            stderr( "$projectid: '$lccn' != '{$lccn_for_projectid[$projectid]}'\n" );
        }
    }
    else
    {
        $lccn_for_projectid[$projectid] = $lccn;
    }
}

$res = dpsql_query("
    SELECT projectid, postednum, state
    FROM projects
    ORDER BY projectid
");
$lccns_for_postednum = array();
while ( list($projectid,$postednum,$state) = mysql_fetch_row($res) )
{
    $is_posted = ($state == 'proj_submit_pgposted');
    $has_postednum = (!is_null($postednum));
    if ( $is_posted != $has_postednum )
    {
        stderr("odd: $projectid $postednum $state\n");
    }
    
    if ($has_postednum)
    {
        $lccn = $lccn_for_projectid[$projectid];
        if ( is_null($lccn) ) $lccn = 'NULL'; // project dir missing, or didn't have dc.xml file, or dc.xml didn't have source/LCCN line.
        if (0)
        {
            // echo "$projectid\t$postednum\t$lccn\n";
            echo "$postednum\t$lccn\n";
        }
        else
        {
            if ( $lccn == 'NULL' || $lccn == 'invalid' ) continue;

            if ( array_key_exists( $postednum, $lccns_for_postednum ) )
            {
                if ( in_array($lccn, $lccns_for_postednum[$postednum]) )
                {
                    // already there
                }
                else
                {
                    $lccns_for_postednum[$postednum][] = $lccn;
                }
            }
            else
            {
                $lccns_for_postednum[$postednum] = array($lccn);
            }
        }
    }
}

echo "PG num\tLCCN\n";
foreach ( $lccns_for_postednum as $postednum => $lccns )
{
    echo "$postednum\t", implode("\t", $lccns), "\n";
}

// vim: sw=4 ts=4 expandtab
?>
