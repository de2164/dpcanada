<?PHP
$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'dpsql.inc');
include_once('dkretz_my_projects_query.inc');
new dbConnect;

if (1)
{
    $sql = testsql('JHowse');
    // dpsql_dump_query("EXPLAIN $sql");
    echo "EXPLAIN $sql\n";
    exit;
}

assert_options(ASSERT_BAIL, 1);

$watch = new Stopwatch;
$watch->start();

$usernames = array(
    'ortonmc','JHowse','dkretz','jmdyck',
    'ortonmc','JHowse','dkretz','jmdyck',
);

// $usernames = array('Lucy24');

foreach( $usernames as $username )
{
    $sql = testsql($username);

    $t1 = $watch->read();
    $res = mysql_query($sql) or die(mysql_error());
    $t2 = $watch->read();

    $n_rows = mysql_num_rows($res);
    mysql_free_result($res);

    $t_diff = $t2 - $t1;
    echo sprintf( "%10s: %4d rows, %6.2f sec\n", $username, $n_rows, $t_diff );
}


// vim: sw=4 ts=4 expandtab
?>
