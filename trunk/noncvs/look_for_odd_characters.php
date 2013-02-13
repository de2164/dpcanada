<?PHP

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$res = mysql_query("
    SELECT projectid
    FROM projects
    WHERE archived != 1 AND state != 'project_delete'
    AND nameofwork LIKE '%Britannica%'
    ORDER BY projectid
") or die(mysql_error());

$n_projects = mysql_num_rows($res);

$template = array();
for ( $ord = 0; $ord <= 255; $ord++ )
{
    $template[chr($ord)] = 0;
}

$total_count_for_char_ = $template;


$i = 0;
while ( list($projectid) = mysql_fetch_row($res) )
{
    $i += 1;
    // if ( $i < 29 ) continue;
    if ( $i>=100 ) break;
    echo sprintf( "%s/%s: %s:", str_pad($i,strlen($n_projects),'0',STR_PAD_LEFT), $n_projects, $projectid );

    $res2 = mysql_query("
        SELECT * FROM $projectid
        ORDER BY image
    ");
    if ( $res2 === FALSE )
    {
        echo " odd, SELECT failed\n";
        continue;
    }
    echo sprintf( " (%3d pages)", mysql_num_rows($res2) );

    while ( $page = mysql_fetch_assoc($res2) )
    {
        $image = $page['image'];

        foreach ( $page as $field_name => $field_value )
        {
            if ( preg_match( '/^(master|round\d+)_text$/', $field_name ) )
            {
                // This is a page-text field.
                if ( $field_name != 'master_text' ) continue;

                // All low control characters except for LF (x0A) and CR (x0D),
                // plus all high control characters.
                $p = '\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F';
                $c = "\x00..\x1F\x7F..\x9F";

                if ( preg_match_all( "/[$p]/", $field_value, $matches, PREG_OFFSET_CAPTURE ) )
                {
                    echo "\n    $image/$field_name:\n";

                    $cell_count_for_char_ = $template;
                    foreach( $matches[0] as $match )
                    {
                        list($char,$offset) = $match;
                        $cell_count_for_char_[$char] += 1;
                        $total_count_for_char_[$char] += 1;
                        if (0)
                        {
                            $ord = ord($char);
                            echo "at $offset: ", dechex($ord), " (", decoct($ord), ")\n";
                            $context = substr($field_value, $offset-10, 21);
                            echo addcslashes($context,$c),"\n";
                            // echo $context, "\n";
                        }
                    }

                    echo "      ";
                    foreach ( $cell_count_for_char_ as $char => $count )
                    {
                        if ($count > 0)
                        {
                            echo sprintf( "  %d x %02X", $count, ord($char) );
                        }
                    }
                }
            }
        }
    }

    echo "\n";
}

echo "counts:\n";
foreach ( $total_count_for_char_ as $char => $count )
{
    if ($count > 0)
    {
        echo sprintf( "%02X %d\n", ord($char), $count );
    }
}

// vim: sw=4 ts=4 expandtab
?>
