<?
include('site_vars.php');	//MS
include('connect.inc');	//MS
$db_Connection=new dbConnect();

//this module sets users inactive who have not been active on the site in 6 months

$old_date = time() - 15768000; // 6 months ago.

$result = mysql_query ("UPDATE `users` SET active = 'no' WHERE t_last_activity < $old_date AND active ='yes'");
$numrows = mysql_affected_rows();

echo "inactivate_users.php set $numrows users who have not been active for 6 months as inactive";

// vim: sw=4 ts=4 expandtab
?>
