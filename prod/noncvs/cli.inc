<?PHP

if ( php_sapi_name() != 'cli' )
{
    die( "This script should only be invoked from the command-line.\n" );
}

// ----------------------------------------------------------------------------

function stderr()
{
    foreach ( func_get_args() as $arg )
    {
        fwrite( STDERR, $arg );
    }
}

function readline($prompt)
{
    stderr($prompt);
    $fp = fopen("php://stdin", "r");
    $in = fgets($fp, 4094); // Maximum windows buffer size
    fclose ($fp);
    return rtrim($in);
}

function get_response( $prompt, $acceptable_repsonses )
{
    while ( TRUE )
    {
        $response = readline($prompt);
        if ( in_array($response, $acceptable_repsonses) )
        {
            return $response;
        }
        else
        {
            echo "you responded '$response', which is not recognized\n";
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
