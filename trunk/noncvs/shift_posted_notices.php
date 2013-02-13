<?PHP

// Retroactively shift posted-notice subscriptions
// from merged projects to merge-target,
// if the merge-target hasn't yet been posted.

$relPath='../c/pinc/';
include_once('cli.inc');
include_once('f_dpsql2.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
new dbConnect;

// Look for cases where people are registered to get notice when a project posts,
// but the project is deleted.

$res = dpsql_query("
    SELECT *, COUNT(*) AS n_interest
    FROM usersettings JOIN projects ON (value=projectid)
    WHERE setting='posted_notice' AND state='project_delete'
    GROUP BY projectid
    ORDER BY projectid
");
while ( $project = mysql_fetch_object($res) )
{
    echo "\n";
    echo "$project->projectid ($project->n_interest interested)\n";
    echo "$project->nameofwork\n";

    // Was this project deleted because it was merged into another project?
    echo "  reason deleted: $project->deletion_reason\n";
    if ( preg_match('/^(merged into|retreaded as|duplicate of|duplicate of part of) (projectID[0-9a-f]{13})\b/', $project->deletion_reason, $matches ) )
    {
        $target_projectid = $matches[2];
        echo "    so looking at $target_projectid...\n";
        $res2 = dpsql_query("
            SELECT nameofwork, state
            FROM projects
            WHERE projectid='$target_projectid'
        ");
        list( $target_nameofwork, $target_state ) = mysql_fetch_row($res2);
        echo "    title: $target_nameofwork\n";
        echo "    state: $target_state\n";
        if ( $target_state == 'proj_submit_pgposted' )
        {
            echo "    so no point shifting the posted_notices over\n";
        }
        else
        {
            echo "    SHIFT!\n";

            echo "\n";
            echo "$project->nameofwork:\n";
            dpsql_tdump_query("
                SELECT username
                FROM usersettings
                WHERE setting='posted_notice' AND value='$project->projectid'
                ORDER BY username
            ");
            echo "\n";
            echo "$target_nameofwork:\n";
            dpsql_tdump_query("
                SELECT username
                FROM usersettings
                WHERE setting='posted_notice' AND value='$target_projectid'
                ORDER BY username
            ");

            $sql = "
                UPDATE usersettings
                SET value='$target_projectid'
                WHERE setting='posted_notice' AND value='$project->projectid'
            ";
            echo "$sql\n";
            dpsql_query($sql);

            echo "\n";
            echo "$project->nameofwork:\n";
            dpsql_tdump_query("
                SELECT username
                FROM usersettings
                WHERE setting='posted_notice' AND value='$project->projectid'
                ORDER BY username
            ");
            echo "\n";
            echo "$target_nameofwork:\n";
            dpsql_tdump_query("
                SELECT username
                FROM usersettings
                WHERE setting='posted_notice' AND value='$target_projectid'
                ORDER BY username
            ");

            echo "\n";
            echo "Check for duplicate subscriptions:\n";
            $res3 = dpsql_query("
                SELECT COUNT(*) AS c, username
                FROM usersettings
                WHERE setting='posted_notice' AND value='$target_projectid'
                GROUP BY username
                HAVING c > 1
                ORDER BY username
            ");
            dpsql_tdump_query_result( $res3 );
            if ( mysql_num_rows($res3) > 0 )
            {
                while( list($count,$username) = mysql_fetch_row($res3) )
                {
                    $limit = $count-1;
                    $sql = "
                        DELETE FROM usersettings
                        WHERE
                            username='$username'
                            AND
                            setting='posted_notice'
                            AND
                            value='$target_projectid'
                        LIMIT $limit
                    ";
                    echo "$sql\n";
                    dpsql_query($sql);
                }
                echo "\n";
                echo "Check for duplicate subscriptions again:\n";
                dpsql_tdump_query("
                    SELECT COUNT(*) AS c, username
                    FROM usersettings
                    WHERE setting='posted_notice' AND value='$target_projectid'
                    GROUP BY username
                    HAVING c > 1
                    ORDER BY username
                ");
            }

            exit;
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
