<?PHP
$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'dpsql.inc');
new dbConnect;

assert_options(ASSERT_BAIL, 1);

$watch = new Stopwatch;
$watch->start();

mysql_query("
    CREATE TEMPORARY TABLE jmd_foo ( username VARCHAR(12) )
") or die(mysql_error());

for ( $i = 1; $i <100000; $i++ )
{
    $username = sprintf( "u%05d", $i );
    mysql_query("
        INSERT INTO jmd_foo SET username='$username'
    ") or die(mysql_error());
}
    
$encoders = array('AES_ENCRYPT', 'ENCODE', 'DES_ENCRYPT');
foreach( $encoders as $encoder )
{
    $t1 = $watch->read();
    mysql_query("
        UPDATE jmd_foo SET username=$encoder(username,'mykey')
    ") or die(mysql_error());
    $t2 = $watch->read();
    echo sprintf( "%-11s: %.2f\n", $encoder, $t2 - $t1 );
}

?>
