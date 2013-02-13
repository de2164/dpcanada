<?PHP
include_once('cli.inc');

$log_file_glob = "/data/logs/apache/access_log.*0";
$log_file_glob = "/data/logs/apache/access_log.1187654400";

$min_increase_for_warning = NULL; // 63;
$min_decrease_for_warning = 420; // the weekly backup tends to cause some requests to block/stall
$ip_addr_of_interest = NULL; // '121.45.225.165'; //= nick0252

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
        // echo "$ip_addr $parseable_time_str\n";
        $timestamp = strtotime($parseable_time_str);
        if ( is_null($max_timestamp_so_far) )
        {
            // first line
            $max_timestamp_so_far = $timestamp;
        }

        $t_diff = $timestamp - $max_timestamp_so_far;
        // echo "$max_timestamp_so_far $t_diff\n";
        // echo $t_diff, "\n";

        if (   !is_null($min_increase_for_warning) && $t_diff >= $min_increase_for_warning 
            || !is_null($min_decrease_for_warning) && $t_diff <= -$min_decrease_for_warning
            || !is_null($ip_addr_of_interest)      && $ip_addr == $ip_addr_of_interest
        )
        {
            echo sprintf(
                "L=%06s IP=%-15s T=%s d=%5d (from L=%06d): %s",
                $i, $ip_addr, $raw_time_str, $t_diff, $i_for_max_timestamp_so_far, $line );
            // reasonable causes for long delay
            // GET /d/download_tmp/projectIDxxx_images.zip
            // GET /c/crontab/archive_projects.php
        }

        if ( $t_diff > 0 )
        {
            $max_timestamp_so_far = $timestamp;
        }
        if ( $t_diff >= 0 )
        {
            $i_for_max_timestamp_so_far = $i;
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
