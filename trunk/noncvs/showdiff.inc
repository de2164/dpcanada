<?PHP

function showdiff( $L_text, $R_text )
{
    if (0)
    {
        // Show the whole texts.
        // (Generally more output than is useful.)
        echo "L: '", addcslashes($L_text,"\0..\37"), "'\n";
        echo "R: '", addcslashes($R_text,"\0..\37"), "'\n";
    }

    if (0)
    {
        // Show the first point where the two texts differ.
        for ( $i = 0; $i<strlen($L_text); $i++ )
        {
            if ( $L_text{$i} != $R_text{$i} )
            {
                echo addcslashes( substr($L_text, max(0,$i-1), 25), "\0..\37" ), "\n";
                echo addcslashes( substr($R_text, max(0,$i-1), 25), "\0..\37" ), "\n";
                break;
            }
        }
    }

    if (1)
    {
        file_put_contents('/tmp/showdiff_L', $L_text);
        file_put_contents('/tmp/showdiff_R', $R_text);
        passthru('vim -d /tmp/showdiff_L /tmp/showdiff_R');
        // passthru('diff /tmp/showdiff_L /tmp/showdiff_R');
        // passthru('diff --side-by-side /tmp/showdiff_L /tmp/showdiff_R');
    }
}

function wdiff($str_L, $str_R)
{
    $pid = posix_getpid();
    $str_L = rtrim( preg_replace('/\s+/', "\n", $str_L), "\n" );
    $str_R = rtrim( preg_replace('/\s+/', "\n", $str_R), "\n" );
    $fn_L = "/tmp/wdiff_L_$pid";
    $fn_R = "/tmp/wdiff_R_$pid";
    file_put_contents($fn_L, $str_L);
    file_put_contents($fn_R, $str_R);
    $d = shell_exec("diff $fn_L $fn_R");
    unlink($fn_L);
    unlink($fn_R);
    return $d;
}

// file_put_contents() is predefined in PHP 5 and on.
if ( !function_exists('file_put_contents') )
{
    function file_put_contents( $filepath, $content )
    {
        $f = fopen($filepath, 'w');
        fwrite($f, $content);
        fclose($f);
    }
}

// vim: sw=4 ts=4 expandtab
?>
