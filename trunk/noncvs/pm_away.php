<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

if ( !user_is_proj_facilitator() )
{
    die("permission denied");
}

/* show all PMs  who haven't been seen for more than m days */

$seen =  @$_GET['seen'];
if ( empty($seen) )
{
  $seen = 100;
}
$round =  @$_GET['round'];
$round_selector = $round;
if ( empty($round) || $round == "Any")
{
  $round = "Any";
  $round_selector = "%";
}

function selected_round($rnum, $round) {
    if ($rnum == $round) {
        return " selected='TRUE'";
    }
    else {
        return "";
    }
}
echo "
<!-- Trigger IE quirks mode -- not that it needs any more quirks! -->
<html><head><title></title><style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: left; }
.number {text-align: right; width: 2em;}
</style>

</head><body>
";

echo "<h2>PMs who have been away for more than $seen days with projects in round $round</h2>\n";

echo "<form method='get'>\n";
echo "<pre>";
echo "show PMs who haven't been seen for more than <input name='seen' type='text' size='10' value='$seen'> days\n";
echo "and who have projects in round: ";
echo " <select name='round'>";
echo " <option value='Any' ". selected_round("Any", $round) . ">Any</option>";
echo " <option value='P1' ". selected_round("P1", $round) . ">P1</option>";
echo " <option value='P2' ". selected_round("P2", $round) . ">P2</option>";
echo " <option value='P3' ". selected_round("P3", $round) . ">P3</option>";
echo " <option value='F1' ". selected_round("F1", $round) . ">F1</option>";
echo " <option value='F2' ". selected_round("F2", $round) . ">F2</option>";
echo " </select>\n";
echo "<input type='submit'>\n";
echo "</pre>";
echo "</form>";

echo "<p>* indicates a user who has been marked as inactive</p>\n";
echo "<table>\n<tr><th>PM</th><th>Projects unavailable</th><th>Projects waiting</th><th>Projects available</th><th>Last seen</th></tr>\n";

$query = "SELECT projects.username, 
                 COUNT(IF((projects.state LIKE '%proj_unavail%'),1,NULL)) AS num_un, 
                 COUNT(IF((projects.state LIKE '%proj_waiting%'),1,NULL)) AS num_wait, 
                 COUNT(IF((projects.state LIKE '%proj_avail%'),1,NULL)) AS num_avail, 
                 (" . time() . "- users.t_last_Activity) AS last_act,
                 users.active as active 
          FROM projects, users 
          WHERE users.username = projects.username
                AND users.t_last_Activity < (UNIX_TIMESTAMP() - (60 * 60 * 24 * $seen))
                AND projects.state LIKE '$round_selector.%'
                GROUP BY projects.username";


$result = mysql_query($query);
$numrow = mysql_numrows($result);
for ( $rownum = 0; $rownum < $numrow; $rownum++ )
{

  $PM = mysql_fetch_assoc($result);
    $is_active = '';
    if ($PM['active'] != 'yes')
    {
        $is_active = ' *';
    }
  echo "<tr><td>" . $PM['username'] . $is_active . " </td>";
  echo "<td class='number'>" .  $PM['num_un'] . "</td>";
  echo "<td class='number'>" .  $PM['num_wait'] . "</td>";
  echo "<td class='number'>" .  $PM['num_avail'] . "</td>";
  echo "<td class='number'>" . round($PM['last_act']/ (60 * 60 * 24)) . "</td>";
  echo "</tr>\n";

}

echo "</table>\n";

