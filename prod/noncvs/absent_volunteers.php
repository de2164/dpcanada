<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

// show volunteers who have a specified access and who haven't
// been on site for a specifed period

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

$seen =  @$_GET['seen'];
if ( empty($seen) )
{
  $seen = 365;
}

$round =  @$_GET['round'];
if ( empty($round) )
{
  $round = 'P3';
}

echo "
<!-- Trigger IE quirks mode -- not that it needs any more quirks! -->
<html>
<head>
<title>Absent volunteers</title>
<style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: left; }
.number {text-align: right; width: 2em;}
</style>
</head>
<body>
";

echo "
<h2>Absent volunteers</h2>\n";

echo "<form method='get'>\n";
echo "<pre>\n";
echo "Days:   <input name='seen' type='text' size='10' value='$seen'> days\n";
echo "Access: <select name='round'>
             <option value='P3'>P3</option>
             <option value='F2'>F2</option>
             <option value='PPV'>PPV</option>
             <option value='P2_mentor'>Mentor</option>
             </select>\n";
echo "<input type='submit'>\n";
echo "</pre>\n";
echo "</form>\n";

echo "<p>Showing volunteers who haven't been seen for more than $seen days and who have access to $round.</p>";

echo "<table>\n<tr><th>Volunteer</th><th>Last seen</th><tr>\n";

$query = "SELECT u_id,
                 users.username AS username, 
                 (" . time() . "- users.t_last_Activity) AS last_act 
          FROM users, usersettings
          WHERE users.t_last_Activity < (UNIX_TIMESTAMP() - (60 * 60 * 24 * $seen))
                AND users.username = usersettings.username 
                AND usersettings.setting = '$round.access'
                AND usersettings.value = 'yes'";

$result = mysql_query($query);
$numrow = mysql_numrows($result);
for ( $rownum = 0; $rownum < $numrow; $rownum++ )
{

  $volunteer = mysql_fetch_assoc($result);
  echo "<tr><td><a href='http://www.pgdp.net/c/stats/members/mdetail.php?id=" . $volunteer['u_id'] . "'>" . $volunteer['username'] . "</a></td>";
  echo "<td class='number'>" . round($volunteer['last_act']/ (60 * 60 * 24)) . "</td>";
  echo "</tr>\n";
}


echo "</table>\n";


echo "</body>\n</html>";

// vim: sw=4 ts=4 expandtab
?>
