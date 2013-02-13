<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');

error_reporting(E_ALL);

$n_days = 8;

echo "<head><title>Projects Posted</title></head>\n";

echo "<h2>Projects posted</h2>\n";
echo "<h3>(Projects that have been posted to PG within the last $n_days days)</h3>\n";

$t_now = time();
$t_cutoff = $t_now - $n_days * 60 * 60 * 24;

$publish[-1] = "Unknown";
$publish[0]  = "No";
$publish[1]  = "Yes";

$query ="
        SELECT project_events.projectid, projects.nameofwork, projects.postednum, 
               image_sources.display_name, image_sources.ok_show_images,
               DATE_FORMAT(FROM_UNIXTIME(project_events.timestamp), '%a&nbsp;%d&nbsp;%b')
        FROM project_events, projects 
            LEFT JOIN image_sources ON projects.image_source = image_sources.code_name
        WHERE project_events.timestamp > $t_cutoff
            AND project_events.event_type = 'transition'
            AND project_events.details2 = 'proj_submit_pgposted'
            AND project_events.projectid = projects.projectid
            AND projects.state = 'proj_submit_pgposted'
        ORDER BY project_events.timestamp DESC
        ";

$res = dpsql_query($query);
if ( mysql_num_rows($res) == 0 )
{
    echo "(none!)<br>";
}
else
{
    echo "<table border='1'>\n";
    echo "<tr>\n";
    echo "<th>project</th>\n";
    echo "<th>etext no</th>\n";
    echo "<th>download<br>zipped<br>images</th>\n";
    echo "<th>image source</th>\n";
    echo "<th>OK to publish images?</th>\n";
    echo "<th>posted</th>\n";
    echo "</tr>\n";
    while ( list($projectid, $nameofwork, $postednum, $image_source, $images_ok, $posted_date) = mysql_fetch_row($res) )
    {
        echo "<tr>\n";
        echo "<td><a href='$code_url/project.php?id=$projectid'>$nameofwork</a></td>\n";
        echo "<td><a href='http://www.gutenberg.org/etext/$postednum'>$postednum</a></td>\n";
        echo "<td align='center'><a href='$code_url/tools/download_images.php?projectid=$projectid&amp;dummy={$projectid}images.zip'>zip</a></td>\n";
        if ( isset($image_source) )
        {
            echo "<td>$image_source</td>\n";
            echo "<td>{$publish[$images_ok]}</td>\n";
        }
        else
        {
            echo "<td>Scanned</td>";
            echo "<td>&nbsp;</td>";
        }
        echo "<td >$posted_date</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
}


// vim: sw=4 ts=4 expandtab
?>
