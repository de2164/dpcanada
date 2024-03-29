<?PHP
$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
new dbConnect();

echo "<h1>Cases where multiple projects have the same postednum</h1>\n";

echo "<p>
    When multiple projects have the same postednum,
    this usually means that a single book was split into multiple projects
    to go through the rounds.
    And when this is easy to detect (titles differ only by a digit),
    this page skips over the set.
    However, a shared postednum could also happen
    when a project was mistakenly assigned another project's postednum.
    This page should help find those cases.
</p>

<p>
    Michael and Pauline reviewed postednums in August 2004,
    so we should be good up to 13195.
</p>
";

$res = dpsql_query("
    SELECT postednum, COUNT(*) as c
    FROM projects
    GROUP BY postednum
    HAVING c > 1
    ORDER BY postednum
");
while( list($postednum,$count) = mysql_fetch_row($res) )
{
    if ($postednum == 6000) continue;

    echo "<br>$postednum:\n";

    {
        $res2 = dpsql_query("
            SELECT nameofwork
            FROM projects
            WHERE postednum=$postednum
            ORDER BY nameofwork
        ");
        $titles=array();
        while ( list($title) = mysql_fetch_row($res2) )
        {
            $titles[] = $title;
        }
        list($left,$middles,$right) = factor_strings( $titles );
        if ( strings_count_up( $middles ) )
        {
            echo "skipping '$left&lt;N&gt;$right'...<br>\n";
            continue;
        }
    }

    dpsql_dump_query("
        SELECT FROM_UNIXTIME(modifieddate), state, nameofwork
        FROM projects
        WHERE postednum=$postednum
        ORDER BY nameofwork
    ");
}

function strings_count_up( $strings )
// The given strings contain numerals '1', '2', '3', etc.
{
    for ( $i = 0; $i < count($strings); $i++ )
    {
        if ( $strings[$i] == (''.($i+1)) )
        {
            // good so far
        }
        else
        {
            return FALSE;
        }
    }
    return TRUE;
}

// vim: sw=4 ts=4 expandtab
?>
