<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');

error_reporting(E_ALL);

$n_hours = 24;

echo "<head><title>Neglected Projects</title></head>\n";

echo "<h2>'Neglected' Projects</h2>\n";
echo "<h3>(Projects that are 'available' in some round, but have not had a page done in the last $n_hours hours)</h3>\n";

$t_now = time();
$t_true_cutoff = $t_now - $n_hours * 60 * 60;
$t_wing_cutoff = $t_now - 0.9 * $n_hours * 60 * 60;

echo "<ul>\n";
foreach ( $Round_for_round_id_ as $round )
{
    echo "<li><a href='#$round->id'>$round->id</a></li>\n";
}
echo "</ul>\n";

foreach ( $Round_for_round_id_ as $round )
{
    echo "<a name='$round->id'></a>\n";
    echo "<h4>$round->id</h4>\n";
    $res = dpsql_query("
        SELECT projectid, t_last_page_done, modifieddate, nameofwork
        FROM projects
        WHERE state='$round->project_available_state'
            AND t_last_page_done < $t_wing_cutoff
        ORDER BY t_last_page_done
    ");

    $n_projects = mysql_num_rows($res);
    if ( $n_projects == 0 )
    {
        echo "(none!)<br>";
    }
    else
    {
        $n_true = 0;
        $n_in_wings = 0;
        echo "<table border='1'>\n";
        echo "<tr>\n";
        echo "<th>project</th>\n";
        echo "<th>days since last page done</th>\n";
        echo "<th>days since release</th>\n";
        echo "</tr>\n";
        while ( list($projectid, $t_last_page_done, $t_release, $nameofwork) = mysql_fetch_row($res) )
        {
            if ( $t_last_page_done == 0 )
            {
                $d_since_last_page_done = 'never';
            }
            else
            {
                $d_since_last_page_done =
                    sprintf( "%.1f", ($t_now - $t_last_page_done) / (24*60*60) );
            }

            $d_since_release =
                sprintf( "%.1f", ($t_now - $t_release) / (24*60*60) );

            $bgcolor = ( $t_last_page_done < $t_true_cutoff ? '#ffffff' : '#eeeeee' );
            echo "<tr bgcolor='$bgcolor'>\n";
            echo "<td><a href='$code_url/project.php?id=$projectid'>$nameofwork</a></td>\n";
            echo "<td align='right'>$d_since_last_page_done</td>\n";
            echo "<td align='right'>$d_since_release</td>\n";
            echo "</tr>\n";
            if ( $t_last_page_done < $t_true_cutoff )
            {
                $n_true++;
            }
            else
            {
                $n_in_wings++;
            }
        }
        echo "</table>\n";
        echo "($n_true projects, plus $n_in_wings getting close)\n";
    }
}


// vim: sw=4 ts=4 expandtab
?>
