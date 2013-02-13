<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() )
{
    die("permission denied");
}

# $show_names = user_is_a_sitemanager();
$show_names = true;

$psd = get_project_status_descriptor('PPd');

$projects_r = dpsql_query("SELECT postproofer, COUNT(*) FROM projects WHERE $psd->state_selector AND postproofer is not null GROUP by postproofer");

$recent_r = dpsql_query("SELECT postproofer, COUNT(*) FROM projects WHERE $psd->state_selector AND postproofer is not null AND modifieddate > ".(time() - (60 * 60 * 24 * 7 * 4 * 12))." GROUP by postproofer");

$checkedout_result = dpsql_query("SELECT checkedoutby, COUNT(*) FROM projects WHERE state = 'proj_post_first_checked_out' GROUP BY checkedoutby");

$reserved_r = dpsql_query("SELECT checkedoutby, COUNT(*) FROM projects WHERE state LIKE '%.%' AND checkedoutby is not null AND checkedoutby != '' GROUP BY checkedoutby");



while ( list($username,$projects_pped) = mysql_fetch_row($projects_r) )
{
    $ppers[$username]['pped'] = $projects_pped;
}

while ( list($username, $projects_checkedout) = mysql_fetch_row($checkedout_result) )
{
    $ppers[$username]['checkedout'] = $projects_checkedout;
}

while ( list($username, $projects_reserved) = mysql_fetch_row($reserved_r) )
{
    $ppers[$username]['reserved'] = $projects_reserved;
}

while ( list($username, $projects_recent) = mysql_fetch_row($recent_r) )
{
    $ppers[$username]['recent'] = $projects_recent;
}
$sql_in = "(";

foreach ($ppers as $pper => $foo)
{
    $sql_in .= "'$pper',";
}

$sql_in = substr($sql_in,0,(strlen($sql_in) - 1)) . ")";


$result = dpsql_query("SELECT username,(".time()." - t_last_activity) FROM users WHERE username IN $sql_in");

while (list($username,$last_seen_time) = mysql_fetch_row($result))
{
    $last_seen[$username] = $last_seen_time;
}
$result = dpsql_query("SELECT username, setting, value FROM usersettings WHERE username IN $sql_in AND setting = 'PP.access'");

while (list($username,$setting,$value) = mysql_fetch_row($result))
{
    $is_pper[$username] = ($value == "yes");
}


echo "
<!-- Trigger IE quirks mode -- not that it needs any more quirks! -->
<html><head><title></title><style type='text/css'>table { border-collapse:collapse; }
table td, table th { border:1px solid black; padding:2px; text-align: center; }
.med { background: #f93;}
.high { background: #f88; }
.extreme {background: #f00; }
.mitigate {background: #8f8; }
.irrel {background: #bbb;}".

/* body
  {
  overflow: hidden;
  }
div.content
  {
  height: 100%;
  overflow: auto;
  }
*/
"
</style>
<style type='text/css' media='screen'>
@import url(\"http://www.pgdp.net/noncvs/inc/fixed4all.css\");
</style>

<!--[if IE]>
<style type='text/css' media='screen'>
@import url(\"http://www.pgdp.net/noncvs/inc/fixed4ie.css\");
</style>
<![endif]-->

<script type='text/javascript'>
onload = function() { document.getElementById('content').focus() }
</script>
</head><body>

<div id='content'>


<p>Reserved: 0,<span class='med'>&gt;20</span>,<span class='high'>&gt;40</span>,<span class='extreme'>(2 times number of projects PPed)</span></p>

<p>Checkedout: 0,<span class='med'>&gt;15</span>,<span class='high'>&gt;20</span>,<span class='extreme'>(<b>3</b> times number of projects PPed)</span></p>
<p>Usernames: last seen in less than 2 weeks,<span class='med'>4 weeks</span>,<span class='high'>3 months</span>,<span class='extreme'>over 3 months</span> ... <span class='irrel'>(not seen in over 2 weeks, but no projects reserved or checked-out).</span></p>

<p>For users with a high number of reserved projects, but a checkedout differential below -15, the differential is coloured green. (If they can manage their checkedout projects effectively, then a high number of reservations probably isn't a problem.)</p>

<p style='font-weight: bold;'>Projects recently PPed is coloured red if zero and projects are reserved or checkedout. Recently = within a year.</p>

<p>(Arithmetic conditions disabled for small numbers of projects)</p>

<p>Users who do not have PP access are marked with a *</p>
";

echo "<table>";

function echo_header_line()
{
echo "<tr><th>Username<br/><span style='color:white;background:white;'>302. piggy@baqaqi.chi.il.us (xx.xx days)</span></th><th>Projects PPed</th><th>Projects PPed<br /> recently</th><th>Projects <br />reserved</th><th>Projects <br />checked out</th><th>Checkedout <br />differential</th><th>Checkedout + Reserved <br />differential</th></tr>";
}
echo_header_line();

foreach ($ppers as $pper => $projects)
{
    $ppers[$pper]['checkout_diff'] = ($projects['checkedout'] - $projects['pped']);
    $ppers[$pper]['r_c_diff'] = ( ($projects['checkedout'] + $projects['reserved']) - $projects['pped']);
    $sorter[$pper] = $ppers[$pper]['r_c_diff'];
}

arsort($sorter);
$i =0;
foreach ($sorter as $pper => $projects)
{
    $i++;
    $projects = $ppers[$pper];
    $uname = ($show_names ? $pper : 'x');
    $uclassname = get_user_classname($pper,($projects['reserved'] + $projects['checkedout']));
    echo "<tr><td style='text-align:left;' class='$uclassname'>".($show_names ? "$i. $pper" : $i)." (". round(($last_seen[$pper] / 86400 ),2)  ." days)".($is_pper[$pper] ? " " : " *")."</td>";

    echo "<td>$projects[pped]</td>";
    if (empty($projects['recent']) && !(empty($projects['checkedout']) || empty($projects['reserved'])) && !empty($projects['pped']))
        $recclassname = 'extreme';
    else
        $recclassname = '';
    echo "<td class='$recclassname'>$projects[recent]</td>";
    switch (true)
    {
        case (($projects['reserved'] > (2 * $projects['pped'])) && $projects['reserved'] >= 3):
            $rclassname = 'extreme';
            break;
        case ($projects['reserved'] < 20):
            $rclassname = '';
            break;

        case ($projects['reserved'] < 40):
            $rclassname = 'med';
            break;

        default:
            $rclassname = 'high';
    }
    echo "<td class='$rclassname'><a href='http://www.pgdp.net/c/tools/proofers/my_projects.php?username=$uname#reserved'>$projects[reserved]</a></td>";
    
    switch(true)
    {
        case (($projects['checkedout'] >= (3 * $projects['pped'])) && $projects['checkedout'] >= 3):
            $cclassname = 'extreme';
            break;
        case ($projects['checkedout'] <= 15):
            $cclassname = '';
            break;
        case ($projects['checkedout'] >= 20):
            $cclassname = 'med';
        default:
            $cclassname = 'high';
    }

    echo "<td class='$cclassname'><a href='http://www.pgdp.net/noncvs/user_pp.php?username=$uname'>$projects[checkedout]</a></td>";

    if ($projects['checkout_diff'] <= -15 && !empty($rclassname))
        $cdclassname = 'mitigate';
    else $cdclassname = '';
    echo "<td class='$cdclassname'>".($projects['checkedout'] - $projects['pped'])."</td>";
    echo "<td>".( ($projects['checkedout'] + $projects['reserved']) - $projects['pped']) . "</td>";
    echo "</tr>";

}
echo "</table></div>

<div id='footer'>
<table>";
echo_header_line();

echo "</table></div>

</html>";

function get_user_classname($user,$projects)
{
    global $last_seen;
    $ls = $last_seen[$user];
    switch(true)
    {
        case ($ls <= (60 * 60 * 24 * 7 * 2)):
            $classname = '';
            break;
        case ($ls <= (60 * 60 * 24 * 7 * 4)):
            $classname = 'med';
            break;
        case ($ls <= (60 * 60 * 24 * 7 * 12)):
            $classname = 'high';
            break;
        default:
            $classname = 'extreme';
    }

   if ($classname != '' && $projects == 0)
        $classname = 'irrel'; 

    return $classname;

}


// vim: sw=4 ts=4 expandtab
?>
