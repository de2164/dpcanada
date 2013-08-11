<?PHP

// Show lots of page-diffs.
// (Specifically, show page-diffs for the work done by a particular proofer.)

$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');

if (!user_is_a_sitemanager()) die("permission denied");

$username = 'Firefly Diva';
// $max_n_projects_per_round = 3000;
// $max_n_pages_per_project = 1000;

$suspect_word = 'girl';

echo "<pre>";

echo "<h1>$username</h1>\n";
echo "suspect word: '$suspect_word'\n";

foreach ( $Round_for_round_id_ as $round )
{
    echo "<h1>$round->id</h1>\n";

    $res = mysql_query("
        SELECT DISTINCT projectid
        FROM page_events
        WHERE username='$username' AND event_type='saveAsDone' AND round_id='$round->id'
        ORDER BY projectid
        -- LIMIT $max_n_projects_per_round
    ") or die(mysql_error());

    while ( list($projectid) = mysql_fetch_row($res) )
    {
        echo "\n";
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
        echo "$projectid:\n";

        $res2 = mysql_query("
            SELECT COUNT(*)
            FROM $projectid
            WHERE $round->user_column_name = '$username'
        ");
        if ( $res2 === FALSE )
        {
            echo mysql_error();
            continue;
        }
        list($n) = mysql_fetch_row($res2);
        echo "$n pages proofed by '$username' in '$round-id'\n";

        $res2 = mysql_query("
            SELECT
                image,
                $round->user_column_name,
                $round->prevtext_column_name,
                $round->text_column_name
            FROM $projectid
            WHERE $round->user_column_name = '$username'
                AND INSTR($round->prevtext_column_name,'$suspect_word')
            -- LIMIT $max_n_pages_per_project
        ");
        echo "Of those, ", mysql_num_rows($res2), " contain the suspect word in the prev text.\n";

        while ( list($image,$user,$text1,$text2) = mysql_fetch_row($res2) )
        {
            echo "-------------------------------------------------------------------------\n";
            echo "$image: ($user)\n";
            if ( $text1 == $text2 )
            {
                echo "    no diff\n";
            }
            elseif ( ($c = substr_count($text1, $suspect_word)) == substr_count($text2,$suspect_word) )
            {
                echo "    no diff in the number of occurrences of the suspect word ($c)\n";
            }
            else
            {
                $text1 = preg_replace('/\r\n/', "\n", $text1);
                $text2 = preg_replace('/\r\n/', "\n", $text2);
                file_put_contents('/tmp/many_diffs_1', $text1);
                file_put_contents('/tmp/many_diffs_2', $text2);
                system( 'diff --side-by-side /tmp/many_diffs_1 /tmp/many_diffs_2' );
                echo "\n";
            }
        }
    }
}

echo "</pre>";

function file_put_contents( $filename, $data )
{
    $f = fopen($filename,'w');
    fwrite($f, $data);
    fclose($f);
}


// vim: sw=4 ts=4 expandtab
?>
