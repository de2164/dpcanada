<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once('../includes/team.inc');

if (empty($_GET['otid'])) { $otid = 0; } else { $otid = $_GET['otid']; }
if (empty($_GET['tid'])) { die("parameter 'tid' is empty"); } else { $tid = $_GET['tid']; }

	if ($userP['team_1'] != $tid && $userP['team_2'] != $tid && $userP['team_3'] != $tid) {
		if ($userP['team_1'] == 0 || $otid == 1) {
			$teamResult = mysql_query("UPDATE users SET team_1 = $tid WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			mysql_query("UPDATE user_teams SET latestUser = ".$userP['u_id'].", member_count = member_count+1, active_members = active_members+1 WHERE id = $tid");
			if ($otid != 0) { mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = ".$userP['team_1'].""); }
			$redirect_team = 1;
		} elseif ($userP['team_2'] == 0 || $otid == 2) {
			$teamResult = mysql_query("UPDATE users SET team_2 = $tid WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			mysql_query("UPDATE user_teams SET latestUser = ".$userP['u_id'].", member_count = member_count+1, active_members = active_members+1 WHERE id = $tid");
			if ($otid != 0) { mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = ".$userP['team_2'].""); }
			$redirect_team = 1;
		} elseif ($userP['team_3'] == 0 || $otid == 3) {
			$teamResult = mysql_query("UPDATE users SET team_3 = $tid WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			mysql_query("UPDATE user_teams SET latestUser = ".$userP['u_id'].", member_count = member_count+1, active_members = active_members+1 WHERE id = $tid");
			if ($otid != 0) { mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = ".$userP['team_3'].""); }
			$redirect_team = 1;
		} else {
			include_once($relPath.'theme.inc');
			$title = _("Three Team Maximum!");
			theme($title, "header");
			echo "<br><center>";
			echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='3' style='border-collapse: collapse' width='95%'>";
			echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='3'><b><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."'>"._("Three Team Maximum")."</font></center></b></td></tr>";
			echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3'><center><font face='".$theme['font_mainbody']."' color='".$theme['color_mainbody_font']."' size='2'>"._("You have already joined three teams.<br>Which team would you like to replace?")."</font></center></td></tr>";
			echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_1']."'");
			echo "<td width='33%'><center><b><a href='jointeam.php?tid=$tid&otid=1'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_2']."'");
			echo "<td width='33%'><center><b><a href='jointeam.php?tid=$tid&otid=2'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_3']."'");
			echo "<td width='34%'><center><b><a href='jointeam.php?tid=$tid&otid=3'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			echo "</tr><tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='3'><center><b><a href='../teams/tdetail.php?tid=$tid'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>"._("Do Not Join Team")."</font></a></b></center></td></tr></table></center>";
			theme("", "footer");
		}
	} else {
		$title = _("Unable to Join the Team");
		$desc = _("You are already a member of this team....");

		metarefresh(4,"../teams/tdetail.php?tid=$tid", $title, $desc);
		$redirect_team = 0;
	}

if ($redirect_team == 1) {
	dpsession_set_preferences_from_db();
	$title = _("Join the Team");
	$desc = _("Joining the team....");
	metarefresh(0,"../teams/tdetail.php?tid=$tid",$title, $desc);
}
?>
