<?PHP

// After doing this:
//     UPDATE users
//     SET date_created = CONV(SUBSTR(id,7),16,10)/(1024*1024)
//     WHERE id BETWEEN 'userID39d81af146b57' AND 'userID3a5576bf3a2f9';
// we still need to correct the corresponding phpbb_users.user_regdate entries...

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$res = dpsql_query("
    SELECT users.username, user_id, date_created, user_regdate
    FROM phpbb_users JOIN users USING (username)
    WHERE FROM_UNIXTIME(user_regdate) = '2002-10-19 00:00:00'
    ORDER BY user_id
");
while ( list($username,$user_id,$date_created,$user_regdate) = mysql_fetch_row($res) )
{
    echo "\n";
    echo sprintf( "%13s %3s %s %s\n",
        $username,
        $user_id,
        date('Y-m-d H:i:s',$date_created),
        date('Y-m-d H:i:s',$user_regdate)
    );
    $sql = "UPDATE phpbb_users SET user_regdate = $date_created WHERE user_id = $user_id";
    echo "$sql\n";
    dpsql_query($sql);
}

// vim: sw=4 ts=4 expandtab
?>
