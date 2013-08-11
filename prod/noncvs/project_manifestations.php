<?PHP
// There are numerous ways in which a project can manifest:
// -- a row in the 'projects' table,
// -- a page-table in the database, 
// -- a project directory in the filesystem,
// -- a discussion topic in the forums.
// -- a mention in marc_records.projectid, page_events.projectid, project_events.projectid, project_pages.projectid
// This script considers the first three.
// Finds all such manifestations,
// and categorizes projects according to what kind of manifestations they have.

error_reporting(E_ALL);

$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
new dbConnect;

// A row in the 'projects' table.
$res = mysql_query("
    SELECT projectid
    FROM projects
") or die(mysql_error());
$from_projects_table = dpsql_fetch_all_keyed($res);

// A page-table in the database.
$from_pages_tables = array();
$res = mysql_query("
    SHOW TABLES
") or die(mysql_error());
while (list($table_name) = mysql_fetch_row($res) )
{
    if ( startswith($table_name,'projectID') or preg_match( '/^\d+$/', $table_name ) )
    {
        $from_page_tables[$table_name] = 1;
    }
}

// A project directory in the filesystem
$from_projects_dir = array();
$dir_handle = opendir($projects_dir);
while ( ( $x = readdir($dir_handle) ) !== FALSE )
{
    if ( startswith($x,'projectID') or preg_match( '/^\d+$/', $x ) )
    {
        $from_projects_dir[$x] = 1;
    }
}
closedir($dir_handle);

// -----------------------------------------------

$something = array();
for ( $in1 = 0; $in1 <= 1; $in1++ )
{
    for ( $in2 = 0; $in2 <= 1; $in2++ )
    {
        for ( $in3 = 0; $in3 <= 1; $in3++ )
        {
            $something[$in1][$in2][$in3] = array();
        }
    }
}
# $from_any = array_merge( $from_projects_table, $from_page_tables, $from_projects_dir );
$from_any = $from_projects_table + $from_page_tables + $from_projects_dir;
echo "Manifestations of " . count($from_any) . " projects.\n";
foreach ( $from_any as $projectid => $dummy )
{
    $in1 = array_key_exists($projectid, $from_projects_table);
    $in2 = array_key_exists($projectid, $from_page_tables);
    $in3 = array_key_exists($projectid, $from_projects_dir);

    $something[$in1][$in2][$in3][] = $projectid;
}

for ( $pass = 1; $pass <= 2; $pass++ )
{
    if ($pass == 1)
    {
        echo "<table border=1>\n";
        echo "<tr>";
        echo "<th>In projects table</th>";
        echo "<th>page-table exists</th>";
        echo "<th>project dir exists</th>";
        echo "<th>count</th>";
        echo "</tr>";
    }
    else
    {
        echo "<pre>\n";
    }
    for ( $in1 = 0; $in1 <= 1; $in1++ )
    {
        for ( $in2 = 0; $in2 <= 1; $in2++ )
        {
            for ( $in3 = 0; $in3 <= 1; $in3++ )
            {
                $count = count($something[$in1][$in2][$in3]);

                if ( $in1 && $in2 && $in3 )
                {
                    // manifested in all three ways: an active project.
                    $bgcolor = 'green';
                }
                else if ( $in1 && !$in2 && !$in3 )
                {
                    // only in projects table: an archived project
                    $bgcolor = 'green';
                }
                else if ( $count == 0 )
                {
                    $bgcolor = 'white';
                }
                else
                {
                    $bgcolor = 'red';
                }

                $yn1 = ( $in1 ? 'yes' : 'no' );
                $yn2 = ( $in2 ? 'yes' : 'no' );
                $yn3 = ( $in3 ? 'yes' : 'no' );

                if ($pass == 1)
                {
                    echo "<tr bgcolor='$bgcolor'>";
                    echo "<td align='center'>$yn1</td>";
                    echo "<td align='center'>$yn2</td>";
                    echo "<td align='center'>$yn3</td>";
                    echo "<td align='center'>$count</td>";
                    echo "</tr>\n";
                }
                else
                {
                    if ($bgcolor == 'red')
                    {
                        echo "\n";
                        echo "$yn1 $yn2 $yn3\n";
                        foreach ($something[$in1][$in2][$in3] as $projectid )
                        {
                                echo "$projectid";
                                if ($in1)
                                {
                                    $res = mysql_query("
                                        SELECT nameofwork
                                        FROM projects
                                        WHERE projectid='$projectid'
                                    ") or die(mysql_error());
                                    echo "  ";
                                    echo "<a href='$code_url/project.php?id=$projectid'>";
                                    echo '"' . mysql_result($res,0) . '"';
                                    echo "</a>";

                                }
                                echo "\n";
                        }
                    }
                }
            }
        }
    }
    if ($pass == 1)
    {
        echo "</table>\n";
    }
    else
    {
        echo "</pre>\n";
    }
}

// vim: sw=4 ts=4 expandtab
?>
