<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

/* show all PPers that have projects older than n days,
   and who haven't been seen for more than m days */

$days =  @$_GET['days'];
$seen =  @$_GET['seen'];
if ( empty($days) )
{
  $days = 100;
}
if ( empty($seen) )
{
  $seen = 0;
}
echo "
<!-- Trigger IE quirks mode -- not that it needs any more quirks! -->
<html><head><title></title><style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: left; }
.number {text-align: right; width: 2em;}
</style>

</head><body>
";

echo "<h2>PPers with PP projects older than $days days</h2>\n";

echo "<form method='get'>\n";
echo "<pre>";
echo "show PPers with projects older than <input name='days' type='text' size='10' value='$days'> days\n";
echo "and who haven't been seen for more than <input name='seen' type='text' size='10' value='$seen'> days\n";
echo "<input type='submit'>\n";
echo "</pre>";
echo "</form>";

echo "<p>* indicates a user who has been marked as inactive</p>\n";

echo "<table>\n<tr><th>PPer</th><th>Projects</th><th>Last seen</th></tr>\n";

$query = "SELECT checkedoutby, 
                 COUNT(*) AS num, 
                 (" . time() . "- users.t_last_Activity) AS last_act,
                 users.active as active
          FROM projects, users 
          WHERE state = 'proj_post_first_checked_out' 
                AND modifieddate < (UNIX_TIMESTAMP() - (60 * 60 * 24 * $days)) 
                AND users.username = projects.checkedoutby 
                AND users.t_last_Activity < (UNIX_TIMESTAMP() - (60 * 60 * 24 * $seen))
                GROUP BY checkedoutby";

$result = mysql_query($query);
$numrow = mysql_numrows($result);
for ( $rownum = 0; $rownum < $numrow; $rownum++ )
{

  $PPer = mysql_fetch_assoc($result);
    $is_active = '';
    if ($PPer['active'] != 'yes')
    {
        $is_active = ' *';
    }

  echo "<tr><td><a href='user_pp.php?username=" . $PPer['checkedoutby'] . "'>" . 
      $PPer['checkedoutby'] . "</a>" . 
      $is_active . "</td>";
  echo "<td class='number'>" .  $PPer['num'] . "</td>";
  echo "<td class='number'>" . round($PPer['last_act']/ (60 * 60 * 24)) . "</td>";
  echo "</tr>\n";

}

echo "</table>\n";