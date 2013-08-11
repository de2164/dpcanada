<?
$relPath = '../c/pinc/';

include_once($relPath . 'dp_main.inc');
include_once($relPath . 'site_vars.php');
include_once($relPath . 'dpsql.inc');
include_once($relPath . 'connect.inc');
include_once($relPath . 'user_is.inc');
include_once($relPath . 'theme.inc');
include_once($relPath . 'prefs_options.inc'); // PRIVACY_* constants

if (!( user_is_a_sitemanager() || user_is_proj_facilitator() ))
{
    echo "You are not authorized to invoke this script.";
    exit;
}

theme('Old newbies','header');

echo "<br /><br />";

$prefill['from'] = isset($_GET['from']) ? $_GET['from'] : '6 weeks ago';
$prefill['to'] = isset($_GET['to']) ? $_GET['to'] : 'last week';

?>
    <fieldset>
    <legend>Choose dates</legend>
    <form method='get'>
    <table border='0'>
    <tr>
        <td style='font-size: small;'>
            Earliest date: <input type='text' name='from' value='<?=$prefill['from']?>' /><br />
            e.g., "2 months ago", "18th March 1985", "the beginning of time"</td>
        <td style='font-size: small;' align='right'>
            Cut-off date: <input type='text' name='to' value='<?=$prefill['to']?>' /><br />
            e.g., "now", "yesterday"</td>
    </tr>
    </table>
    <input type='submit' value='Okay, show me the newbies' />
    </form>
    <p>(Users who have set their privacy setting to Anonymous will not be shown.)</p>
    </fieldset><br /><br />
<?



if ( isset($_GET['to']) )
{
    $from = strtotime($_GET['from']);
    $to = strtotime($_GET['to']);
    
    $sql = "SELECT 
         DISTINCT users.username AS 'User',
         CONCAT('<a href=\"$forums_url/privmsg.php?mode=post&u=',phpbb_users.`user_id`,'\">Send PM</a>') as 'Send PM',
         FROM_UNIXTIME(users.date_created,'%D %M %Y') as 'Date Joined',
		 current_tallies.tally_value AS 'P1 page count'

         FROM phpbb_users,users,current_tallies

         WHERE 
             current_tallies.tally_name = 'P1' 
             AND current_tallies.holder_type = 'U' 
             AND current_tallies.holder_id = users.u_id
             AND users.u_privacy != ".PRIVACY_ANONYMOUS."
             AND phpbb_users.username = users.username
             AND users.date_created BETWEEN $from AND $to
             
             
         ORDER BY users.date_created";

    dpsql_dump_themed_query($sql);
}

theme('','footer');
?>
