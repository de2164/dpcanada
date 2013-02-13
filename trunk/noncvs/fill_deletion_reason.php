<?PHP

// Look for projects with an empty deletion_reason, and try to guess what it should be.

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$res = mysql_query("
    SELECT *
    FROM projects
    WHERE state='project_delete' and deletion_reason=''
    ORDER BY nameofwork
") or die(mysql_error());

$probably_parts = array();
while( $project = mysql_fetch_object($res) )
{
    if ( preg_match( '/(.+) [[(]\D*\d+ of \d+/', $project->nameofwork, $matches ) )
    {
        list($title,$base) = $matches;
        @$probably_parts[$base][] = $project;
    }
    else if ( preg_match( '/(.+) \[missing pages?\]/i', $project->nameofwork, $matches ) )
    {
        list($title,$base) = $matches;
        something( $base, array($project) );
    }
}

foreach( $probably_parts as $base => $projects )
{
    something( $base, $projects );
}

function something( $base, $del_projects )
{
    echo "\n";
    echo "$base\n";
    echo "    ", count($del_projects), " deleted:\n";

    $deleted_projectids = array();
    foreach ( $del_projects as $project )
    {
        echo "        $project->projectid $project->nameofwork\n";
        $deleted_projectids[] = $project->projectid;
    }

    $escbase = mysql_escape_string($base);
    $res2 = mysql_query("
        SELECT * 
        FROM projects
        WHERE nameofwork LIKE '$escbase%'
            AND state != 'project_delete'
    ") or die(mysql_error());
    $n_nondeleted_same_base = mysql_num_rows($res2);
    echo "    $n_nondeleted_same_base not deleted:\n";
    while ( $nond = mysql_fetch_object($res2) )
    {
        echo "        $nond->projectid $nond->nameofwork (($nond->state))\n";
        $nondeleted = $nond;
    }
    echo "\n";

    if ( $n_nondeleted_same_base == 1 )
    {
        $r = get_response("say that deleteds were merged into non-deleted? ", array('y','n') );
        if ( $r == 'y' )
        {
            $deleted_projectids_s = surround_and_join($deleted_projectids, "'", "'", ",");
            $sql = "
                UPDATE projects
                SET deletion_reason='merged into $nondeleted->projectid'
                WHERE state = 'project_delete'
                    AND projectid IN ($deleted_projectids_s)
            ";
            mysql_query($sql);
            $n_rows_affected = mysql_affected_rows();
            echo "$n_rows_affected rows affected.\n";
            if ( $n_rows_affected != count($del_projects) )
            {
                echo "mismatch; aborting\n";
            }
        }
    }
    else
    {
        echo "hm\n";
    }

}


// vim: sw=4 ts=4 expandtab
?>
