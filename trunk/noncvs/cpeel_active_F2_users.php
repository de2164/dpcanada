<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');

echo "<p>There users have been granted F2 access.</p>";

$query="select users.username as username, users.u_id, from_unixtime(last_login) as last_login from usersettings, users where users.username=usersettings.username and setting = 'F2.access' and value ='yes' order by last_login";
$res1=mysql_query($query);

echo "<table>";
echo "<tr><td>Username</td><td>Last Login</td></tr>";
while ( list($username, $uid, $last_login) = mysql_fetch_row($res1) ) {
    echo "<tr><td><a href='http://www.pgdp.net/c/stats/members/mdetail.php?id=$uid'>$username</a></td><td>$last_login</td></tr>";
}

echo "</table>";

?>
