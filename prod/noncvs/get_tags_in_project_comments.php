<?PHP

error_reporting(E_ALL);

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$res = mysql_query("
    SELECT projectid, comments, username, nameofwork
    FROM projects
    ORDER BY username, nameofwork
") or die(mysql_error());

$n_projects = mysql_num_rows($res);

$name_pattern = '[a-z][-_a-z0-9]*';
$attrval = '("[^"]*"|\'[^\']*\'|\S+)';

$count_for_name = array();

$n_echoes = 0;
$i = 0;
while ( list($projectid,$comments,$username,$name_patternofwork) = mysql_fetch_row($res) )
{
    $i += 1;
    $header = sprintf( "\n%05d/%05d: %s (%s) %s\n", $i, $n_projects, $projectid, $username, substr($name_patternofwork,0,30) );
    $header_shown = FALSE;

    // Pull out anything that looks remotely like a tag.
    // [For now, allow comments containing angle brackets.]
    $n = preg_match_all('/<!--.*?-->|<[^>]*(>|$)/s', $comments, $matches );
    // First, look for malformed tags.
    foreach ( $matches[0] as $tag )
    {
        if ( preg_match('@^<!DOCTYPE html public "[^"]+"(\s+"[^"]+")?>$@i', $tag ) )
        {
            // doctype decl
        }
        elseif ( preg_match('@^<!--.*-->$@s', $tag ) )
        {
            // comment
        }
        elseif ( preg_match("@^</($name_pattern)>$@i", $tag, $tagmatches ) )
        {
            // end tag
            $name = $tagmatches[1];
            @$count_for_name[strtolower($name)] += 1;
        }
        else if ( preg_match("@^<($name_pattern)(\\s+$name_pattern\\s*=\\s*$attrval)*\\s*/?>$@i", $tag, $tagmatches ) )
        {
            // start tag or empty tag
            $name = $tagmatches[1];
            @$count_for_name[strtolower($name)] += 1;
        }
        else
        {
            // malformed tag
            // Skip them for now.
            continue;

            if ( !$header_shown ) { echo $header; $header_shown = TRUE; }
            echo "  ", addcslashes($tag,"\0..\37"), "\n";
            $n_echoes += 1;
            // if ( $n_echoes >= 300 ) exit;
        }

    }

    // echo "\n";
    // if ($n>0) break;
}

echo "counts:\n";
ksort($count_for_name);
foreach ( $count_for_name as $tag => $count )
{
    echo sprintf("%6d %s\n", $count, $tag );
}

// vim: sw=4 ts=4 expandtab
?>
