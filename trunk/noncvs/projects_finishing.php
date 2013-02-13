<?PHP
$relPath='../c/pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');

if (!user_is_a_sitemanager() && $pguser != 'DaveKline' && $pguser != 'PM QC') die("permission denied");

if ( !isset($_GET['lo_y']) )
{
    header( 'Content-type: text/html' );
    echo "
    <pre>
    <form>
    Get list of projects finishing round <input type='text' name='round_id' size='3' value='P2'> between
    <input type='text' name='lo_y' size='4' value='2005'>-<input type='text' name='lo_m' size='2' value='11'>-<input type='text' name='lo_d' size='2' value='1'> and
    <input type='text' name='hi_y' size='4' value='2005'>-<input type='text' name='hi_m' size='2' value='11'>-<input type='text' name='hi_d' size='2' value='7'> inclusive.
    <input type='submit'>
    (Don't be suprised if it takes a minute or so.)
    </form>
    </pre>
    ";
}
else
{
    header( 'Content-type: text/plain' );
    /*
    $round_id = 'P2';

    $lo_y = '2005';
    $lo_m = '10';
    $lo_d = '5';

    $hi_y = '2005';
    $hi_m = '10';
    $hi_d = '11';
    */
    $round_id = $_GET['round_id'];

    $lo_y = $_GET['lo_y'];
    $lo_m = $_GET['lo_m'];
    $lo_d = $_GET['lo_d'];

    $hi_y = $_GET['hi_y'];
    $hi_m = $_GET['hi_m'];
    $hi_d = $_GET['hi_d'];

    $lo_date = sprintf( '%04d-%02d-%02d', $lo_y, $lo_m, $lo_d );
    $hi_date = sprintf( '%04d-%02d-%02d', $hi_y, $hi_m, $hi_d );

    echo "Projects finishing $round_id between $lo_date to $hi_date inclusive:\n";

    $lo_jd = GregorianToJD( $lo_m, $lo_d, $lo_y );
    $hi_jd = GregorianToJD( $hi_m, $hi_d, $hi_y );

    assert( $lo_jd <= $hi_jd );

    $num_projects_finishing = 0;

    $log_dir = "$dyn_dir/stats/automodify_logs";

    $dirs_to_rm = array();

    for ( $jd = $lo_jd; $jd <= $hi_jd; $jd++ )
    {
        $mdy = JDToGregorian($jd);
        list($m,$d,$y) = split('/',$mdy);
        $y_m_d = sprintf( '%04d-%02d-%02d', $y, $m, $d );
        // echo "$y_m_d\n";

        $y_m = sprintf( '%04d-%02d', $y, $m );
        $month_tgz_path = "$log_dir/am-$y_m.tar.gz";
        if ( file_exists($month_tgz_path) )
        {
            $tmp_dir = "/tmp/projects_finishing_$y_m";
            if ( !is_dir($tmp_dir) )
            {
                mkdir($tmp_dir) || die( "cannot mkdir $tmp_dir" );
                system( "gunzip -c $month_tgz_path | tar --file - --directory $tmp_dir --extract\n" );
                $dirs_to_rm[] = $tmp_dir;
            }
            $dir_for_day_files = $tmp_dir;
        }
        else
        {
            $dir_for_day_files = $log_dir;
        }

        $day_files = glob("$dir_for_day_files/${y_m_d}_*.txt");
        foreach( $day_files as $day_file )
        {
            // echo "$day_file\n";
            $prefix = basename( $day_file, '.txt' );
            $lines = file($day_file);
            foreach ( $lines as $line )
            {
                if ( preg_match( '/^projectid *= (\S+)\s+/', $line, $matches ) )
                {
                    $projectid = $matches[1];
                }
                elseif ( preg_match( '/^nameofwork *= (".+")\s+/', $line, $matches ) )
                {
                    $nameofwork = $matches[1];
                }
                elseif ( preg_match( '/^ *Advancing ".+" to ' . $round_id . '\.proj_done\s+/', $line ) )
                {
                    echo "$prefix $projectid $nameofwork\n";
                    $num_projects_finishing++;
                }
            }

        }
    }

    foreach ( $dirs_to_rm as $dir_to_rm )
    {
        system( "rm -rf $dir_to_rm" );
    }

    echo "$num_projects_finishing projects found\n";
}

// vim: sw=4 ts=4 expandtab
?>
