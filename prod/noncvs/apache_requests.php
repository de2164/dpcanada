<?PHP
include_once('cli.inc');

$log_file_glob = "/data/logs/apache/access_log.*0";

// ---------------------

$number_for_month_abbr = array(
    'Jan' => '01',
    'Feb' => '02',
    'Mar' => '03',
    'Apr' => '04',
    'May' => '05',
    'Jun' => '06',
    'Jul' => '07',
    'Aug' => '08',
    'Sep' => '09',
    'Oct' => '10',
    'Nov' => '11',
    'Dec' => '12',
);

$max_timestamp_so_far = NULL;

foreach ( glob( $log_file_glob ) as $logpath )
{
    $f = fopen($logpath,'r');

    $i = 0;
    while ( ( $line = fgets($f) ) !== FALSE )
    {
        $i++;
        $m = preg_match('!^([\d.]+).*?(\[(\d\d)/(\w\w\w)/(\d\d\d\d):(\d\d:\d\d:\d\d) (-\d\d\d\d)\])!', $line, $matches );
        assert( $m == 1 );
        list($_, $ip_addr, $raw_time_str, $d, $m, $y, $t, $tz) = $matches;
        $m = $number_for_month_abbr[$m];
        $parseable_time_str = "$y-$m-$d $t $tz";
    }
}

// vim: sw=4 ts=4 expandtab
?>
