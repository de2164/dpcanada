<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');

header('Content-type: text/plain');

error_reporting(E_ALL);

if (!user_is_a_sitemanager()) die("permission denied");

$res = dpsql_query("
    SELECT projectid, postednum, htmllink
    FROM projects
    WHERE state='proj_submit_pgposted' AND htmllink != ''
    AND postednum >= 10000
") or die("Aborting");
while ( list($projectid,$postednum,$htmllink) = mysql_fetch_row($res) )
{
    echo "$postednum $htmllink";
    // echo "\n"; continue;
    $x = preg_replace('#(.)#', '\1/', substr($postednum, 0, -1) );
    $possibles =
        all_possible_concatenations(
            array(
                "http://gutenberg.net/",
                "http://gutenberg.net/dirs/",
                "http://ibiblio.unc.edu/pub/docs/books/gutenberg/",
                "http://www.gutenberg.net/",
                "http://www.gutenberg.org/",
                "http://www.gutenberg.org/dirs/",
                "http://www.ibiblio.org/gutenberg/",
            ),
            "{$x}$postednum/$postednum",
            // array( ".txt", "-8.txt", "-0.txt", "-t.tex" )
            // array( ".zip", "-8.zip", "-0.zip", "-8.txt" )
            array( "-h/$postednum-h.htm" )
        );
    foreach( $possibles as $possible )
    {
        if ( $htmllink == $possible )
        {
            dpsql_query("
                UPDATE projects
                SET htmllink=NULL
                WHERE projectid='$projectid'
            ") or die("Aborting");
            echo " DELETED";
            break;
        }
    }
    echo "\n";
}

// vim: sw=4 ts=4 expandtab
?>
