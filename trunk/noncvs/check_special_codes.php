<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc');

if ( !user_is_a_sitemanager() ) die( "permission denied" );

$res2 = mysql_query("
    SELECT spec_code
    FROM special_days
    ORDER BY spec_code
") or die(mysql_error());
// dpsql_dump_query_result($res2);
while ( list($special_code) = mysql_fetch_row($res2) )
{
    $from_days[] = $special_code;
}

$res1 = mysql_query("
    SELECT DISTINCT
        CASE
            WHEN special_code LIKE 'Birthday%' THEN 'Birthday%'
            WHEN special_code LIKE 'Otherday%' THEN 'Otherday%'
            ELSE special_code
        END
    FROM projects
    ORDER BY special_code
") or die(mysql_error());
// dpsql_dump_query_result($res1);
while ( list($special_code) = mysql_fetch_row($res1) )
{
    $from_projects[] = $special_code;
}

$res3 = mysql_query("
    SELECT project_selector
    FROM queue_defns
    WHERE INSTR(project_selector,'special_code')
") or die(mysql_error());

while ( list($project_selector) = mysql_fetch_row($res3) )
{
    $n_occ = substr_count( $project_selector, 'special_code' );
    $n_matches = preg_match_all(
        '/special_code *(=|like|!=) *(["\'])(.*?)\2/',
        $project_selector,
        $matches );
    assert( $n_matches == $n_occ );
    assert( count($matches[3]) == $n_occ );
    foreach ( $matches[3] as $special_code )
    {
        $from_qds[strtolower($special_code)] = $special_code;
    }
}
ksort($from_qds);
$from_qds = array_values($from_qds);
// var_dump($from_qds);

diff_many(
    array( $from_days, $from_projects, $from_qds),
    array( 'special_days.spec_code', 'projects.special_code', 'queue_defns.project_selector' )
);

function diff_many( $arrays, $headers )
// Destructive!
{
    // var_dump($arrays);
    $n = count($arrays);

    echo "<table border='1'>\n";

    echo "<tr>";
    foreach ( $headers as $header )
    {
        echo "<th>$header</th>\n";
    }
    echo "</tr>\n";

    while (1)
    {
        echo "<tr>\n";

        $min_value = NULL;
        for ( $i = 0; $i < $n; $i++ )
        {
            if ( is_null($min_value) or $arrays[$i][0] < $min_value )
            {
                $min_value = $arrays[$i][0];
            }
        }

        // echo "min_value = '$min_value'\n";
        if ( is_null($min_value) ) break;

        for ( $i = 0; $i < $n; $i++ )
        {
            echo "<td>";
            if ( strcasecmp( $arrays[$i][0], $min_value ) == 0 )
            {
                echo $arrays[$i][0];
                array_shift($arrays[$i]);
            }
            else
            {
                echo '&nbsp;';
            }
            echo "</td>";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";
}

// vim: sw=4 ts=4 expandtab
?>
