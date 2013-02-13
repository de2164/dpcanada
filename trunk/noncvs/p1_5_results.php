<?
$relPath = '../c/pinc/';
include_once($relPath . 'site_vars.php');
include_once($relPath . 'dpsql.inc');
include_once($relPath . 'connect.inc');
include_once($relPath . 'theme.inc');
include_once($relPath . 'dp_main.inc');

theme('','header');


$result = dpsql_query("SELECT user_id FROM phpbb_users WHERE username = '$pguser'");
$phpbb_user_id = mysql_result($result,0);

$p15_number = `dc -e "434566456 $phpbb_user_id 17353 * 869046904693103  | 100000000000000 + p"`;
$p15_number = trim($p15_number);

$url = "http://posso.dm.unipi.it/users/traverso/DP-1.5/PersonalData/$p15_number.html";

echo "<p>If there were substantial changes to the pages that you proofread in P1.5 projects,
    then automated feedback will be available to you at this location:</p>

		<p style='text-indent:2em;'><a href='$url'>$url</a></p>
		
		<p>If you didn't proofread any P1.5 pages, or there were no substantial changes to the pages
		that you did proofread, you will see a 'Not Found' error.";

theme('','footer');
?>
