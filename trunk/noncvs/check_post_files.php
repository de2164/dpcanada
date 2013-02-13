<?PHP

// Look for projects that have transitioned to PP,
// and have post files that predate the transition.

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc');
include_once($relPath.'../tools/project_manager/post_files.inc');
new dbConnect;

$suffixes = array(
    '_comments.html',
    '.txt',
    '.zip',
    '_TEI.txt',
    '_TEI.zip',
);

$pad_length=strlen('projectID452aa9a89a060_comments.html');

$res = dpsql_query("
    SELECT *
    FROM projects
    WHERE archived != 1
    ORDER BY projectid
");
while ( $p = mysql_fetch_object($res) )
{
    $project_dir = "$projects_dir/{$p->projectid}";

    $file_stats = array();
    foreach ( $suffixes as $suffix )
    {
        $filename = $p->projectid . $suffix;
        $filepath = "$project_dir/$filename";
        $stat = @stat($filepath);
        if ( $stat !== FALSE )
        {
            $stat['filename'] = $filename;
            $file_stats[$suffix] = $stat;
        }
    }
    if ( count($file_stats) == 0 ) continue;

    if ( preg_match( '/^[PF][123]\.proj_(waiting|avail|unavail)$/', $p->state ) )
    {
        continue;
        // This project is still in the rounds, so it shouldn't have post files yet.
        // Since it does, it's probably an old R1+R2 project that reached PP.waiting
        // and then got "retreaded", and the post files are old.
        // If the post files are owned by dpadmin (503), then it's almost certain.
        echo "$p->projectid $p->state $p->nameofwork\n";
        foreach ( $file_stats as $suffix => $stat )
        {
            $mtime_s = strftime('%Y-%m-%d %H:%M:%S', $stat['mtime']);
            if ( $stat['uid'] == '503' && $stat['gid'] == '503' )
            {
                echo "    rename: {$stat['uid']} {$stat['gid']} $mtime_s {$stat['filename']}\n";
                $cur_path = "$project_dir/{$p->projectid}{$suffix}";
                $new_path = "$project_dir/{$p->projectid}_OLD{$suffix}";
                // echo "        mv $cur_path $new_path\n";
                rename( $cur_path, $new_path );
            }
            else
            {
                echo "    leave : {$stat['uid']} {$stat['gid']} $mtime_s {$stat['filename']}\n";
            }
        }
    }
    else if ( $p->state == 'proj_submit_pgposted' || startswith($p->state,'proj_post_') )
    {
        // We would expect this project to have post files.
        // But were they generated when the project transitioned to PP?
        $res2 = dpsql_query("
            SELECT timestamp
            FROM project_events
            WHERE
                projectid='$p->projectid'
                AND event_type='transition'
                AND details1 NOT LIKE 'proj_post_%'
                AND details2     LIKE 'proj_post_%'
        ");
        $n = mysql_num_rows($res2);
        if ($n == 0)
        {
            // Don't have a record of when it went to PP.
        }
        else if ($n == 1)
        {
            list($trans_time) = mysql_fetch_row($res2);
            $trans_time_s = strftime('%Y-%m-%d %H:%M:%S', $trans_time);
            echo "$p->projectid $trans_time_s $p->state $p->nameofwork\n";

            foreach ( $file_stats as $suffix => $stat )
            {
                if ( $stat['mtime'] < $trans_time )
                {
                    $mtime_s = strftime('%Y-%m-%d %H:%M:%S', $stat['mtime']);
                    echo "    {$stat['uid']} {$stat['gid']} $mtime_s {$stat['filename']}\n";
                }
            }
        }
        else
        {
            echo "??? n=$n $p->projectid $p->state $p->nameofwork\n";
        }
    }
    else
    {
        // very unexpected
        echo "??? $p->projectid $p->state $p->nameofwork\n";
    }
}

exit;
// -----------------------------------------------------------------------------

/*
$res = dpsql_query("
    SELECT project_events.projectid, state, timestamp, nameofwork
    FROM project_events
        JOIN projects USING (projectid)
    WHERE
        event_type='transition'
        AND details1 NOT LIKE 'proj_post_%'
        AND details2     LIKE 'proj_post_%'
        AND archived != 1
        AND state != 'project_delete'
    ORDER BY timestamp
");

echo mysql_num_rows($res), " projects found.\n";

while( list($projectid, $state, $timestamp, $nameofwork) = mysql_fetch_row($res) )
{
    $project_dir = "$projects_dir/$projectid";

    $warnings = array();

    foreach ( $suffixes as $suffix )
    {
        $filename = $projectid . $suffix;

        $filename_s = str_pad($filename,$pad_length);
        $filepath = "$project_dir/$filename";
        $stat = stat($filepath);
        if ( $stat === FALSE )
        {
            $warnings[] = "$filename_s: file does not exist";
        }
        else
        {
            $mtime = $stat['mtime'];
            $mmt = $mtime - $timestamp;
            if ( $mmt >= 0 )
            {
                // file generated after transition, fine
            }
            else
            {
                $warnings[] = "$filename_s: mtime - timestamp = $mmt";
            }
        }
    }

    if ( count($warnings) == 0 ) continue;

    echo "\n";
    $d = strftime('%Y-%m-%d %H:%M:%S', $timestamp);
    echo "$projectid $timestamp ($d) $state $nameofwork\n";
    foreach ( $warnings as $warning )
    {
        echo "        $warning\n";
    }

    continue;

    echo "    generating post files...\n";
    generate_post_files( $projectid, 'F2', 'LE', TRUE, '_NEW_' );

    foreach ( array('_comments.html','.txt') as $suffix )
    {
        $filename_old = $projectid . $suffix;
        $filename_new = $projectid . '_NEW_' . $suffix;
        echo "\n";
        echo "diff $filename_old $filename_new\n";
        passthru( "diff $project_dir/$filename_old $project_dir/$filename_new" );
    }
}
*/

// vim: sw=4 ts=4 expandtab
?>
