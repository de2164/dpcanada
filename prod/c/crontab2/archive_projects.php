<?
include('misc.inc');  //MS
include('site_vars.php');  //MS
include('udb_user.php');  //MS
include('dpsql.inc');  //MS
include('connect.inc');  //MS
include('project_states.inc');  //MS
include('project_events.inc');  //MS
$db_Connection=new dbConnect();

header('Content-type: text/plain');

// Find projects that were posted to PG a while ago
// (that haven't been archived yet), and:
// -- move the project's page-table to the archive database,
// -- move the project's directory out of $projects_dir
//    (for later off-site migration),
// -- mark the project as having been archived.

$dry_run = array_get( $_GET, 'dry_run', '' );
if ($dry_run)
{
    echo "This is a dry run.\n";
}

$result = mysql_query("
    SELECT projectid, FROM_UNIXTIME(modifieddate), nameofwork
    FROM projects
    WHERE
        modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * IF( INSTR(nameofwork,'{P3 Qual}'), 28, 7 )
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
    ORDER BY modifieddate
") or die(mysql_error());

echo "Archiving page-tables for ", mysql_num_rows($result), " projects...\n";

while ( list($projectid, $mod_time, $nameofwork) = mysql_fetch_row($result) )
{
    echo "$projectid  $mod_time  \"$nameofwork\"\n";

    if (!mysql_query("DESCRIBE $projectid"))
    {
        echo "    Table $projectid does not exist.\n";
    }
    elseif ($dry_run)
    {
        echo "    Move table $projectid to $archive_db_name.\n";
    }
    else
    {
        mysql_query("
            ALTER TABLE $projectid
            RENAME AS $archive_db_name.$projectid
        ") or die(mysql_error());
    }

    $project_dir = "$projects_dir/$projectid";
    if (file_exists($project_dir))
    {
        $new_dir = "$archive_projects_dir/$projectid";
        if ($dry_run)
        {
            echo "    Move $project_dir to $new_dir.\n";
        }
        else
        {
            // Remove uncompressed versions of whole-project texts, leaving zips.
            exec( "rm $project_dir/projectID*.txt" );
            rename( $project_dir, $new_dir ) or die( "Unable to move $project_dir to $new_dir" );
        }
    }
    else
    {
        echo "    Warning: $project_dir does not exist.\n";
    }

    if ($dry_run)
    {
        echo "    Mark project as archived.\n";
    }
    else
    {
        mysql_query("
            UPDATE projects
            SET archived = '1'
            WHERE projectid='$projectid'
        ") or die(mysql_error());

        log_project_event( $projectid, '[archiver]', 'archive' );
    }
}


echo "archive_projects.php executed.";

// vim: sw=4 ts=4 expandtab
?>
