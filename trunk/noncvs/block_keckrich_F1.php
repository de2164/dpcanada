<?
// Temporary script to remove F1 access from keckrich
// Currently being reloaded every 12 seconds by donovans browser
$relPath="../c/pinc/";
include($relPath.'misc.inc');
include($relPath.'connect.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

$db_Connection=new dbConnect();

header('Content-type: text/plain');

$result = mysql_query("
    UPDATE usersettings
    SET value = 'no'
    WHERE
    username='keckrich'
    AND
    setting = 'F1.access'
") or die(mysql_error());

$result = mysql_query("
    UPDATE usersettings
    SET value = 'no'
    WHERE
    username='keckrich'
    AND
    setting = 'PP.access'
") or die(mysql_error());

echo "Removed F1 and PP access for keckrich";

// vim: sw=4 ts=4 expandtab
?>
