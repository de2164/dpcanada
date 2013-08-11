<?PHP
$relPath='../c/pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'connect.inc');
new dbConnect;

// include_once($relPath.'dp_main.inc');
// if (!user_is_a_sitemanager()) die("permission denied");

error_reporting(E_ALL);

header("Content-type: text/plain");

$res = mysql_query("
    SELECT projectid, username, state, nameofwork
    FROM projects
    WHERE archived != 1
    ORDER BY projectid
    -- LIMIT 10
") or die(mysql_error());

$kinds = array('page-image', 'generated', 'other', 'ALL');

$n_projects = 0;

echo sprintf( "%-22s %-25s %-25s %-s", "projectid","username","state","nameofwork" );
foreach ( $kinds as $kind )
{
    echo sprintf( "%15s", $kind );
    $total_n_files_of_kind_[$kind]  = 0;
    $total_size_of_files_of_kind_[$kind] = 0;
}
echo "\n";

while ( list($projectid,$user,$state,$title) = mysql_fetch_row($res) )
{
    // echo "\n";
    // echo "$projectid\n";

    if ( !is_dir("$projects_dir/$projectid") )
    {
        // odd: non-archived project does not have directory
        continue;
    }
    chdir( "$projects_dir/$projectid" );

    foreach ( $kinds as $kind )
    {
        $this_proj_n_files_of_kind_[$kind]  = 0;
        $this_proj_size_of_files_of_kind_[$kind] = 0;
    }

    $filenames = glob("*");
    if ( count($filenames) != 0 )
    {
        $res2 = mysql_query("
            SELECT image
            FROM $projectid
        ") or die(mysql_error());
        $page_filenames = array();
        while ( list($page_filename) = mysql_fetch_row($res2) )
        {
            $page_filenames[$page_filename] = 1;
        }

        foreach ($filenames as $filename)
        {
            if ( array_key_exists( $filename, $page_filenames ) )
            {
                $kind = 'page-image';
            }
            elseif ( startswith($filename,$projectid) || $filename == 'dc.xml' )
            {
                $kind = 'generated';
            }
            else
            {
                $kind = 'other';
                // echo "$filename\n";
            }

//            $filesize_bytes = filesize($filename);
            $s = stat($filename); $filesize_bytes = $s['size'];
            $filesize = $filesize_bytes / (1024*1024);

            $this_proj_n_files_of_kind_[$kind] += 1;
            $this_proj_size_of_files_of_kind_[$kind] += $filesize;

            $this_proj_n_files_of_kind_['ALL'] += 1;
            $this_proj_size_of_files_of_kind_['ALL'] += $filesize;

        }
    }

    echo "$projectid" . sprintf("|%-25s|%-25s|",$user,get_medium_label_for_project_state($state));
    foreach ( $kinds as $kind )
    {
        echo sprintf(
            "%4d|  %5.1f |",
            $this_proj_n_files_of_kind_[$kind],
            $this_proj_size_of_files_of_kind_[$kind]
        );

        $total_n_files_of_kind_[$kind] += $this_proj_n_files_of_kind_[$kind];
        $total_size_of_files_of_kind_[$kind] += $this_proj_size_of_files_of_kind_[$kind];
    }
    echo "$title\n";

    $n_projects += 1;
}

echo "\n";
echo "totals:\n";
echo "# projects:      $n_projects\n";
foreach ( $kinds as $kind )
{
    echo sprintf(
        "%10s files: %7d %8.1f_MB (avg %4.2f)\n",
        $kind,
        $total_n_files_of_kind_[$kind],
        $total_size_of_files_of_kind_[$kind],
        $total_size_of_files_of_kind_[$kind] / $total_n_files_of_kind_[$kind]
    );
}

// vim: sw=4 ts=4 expandtab
?>
