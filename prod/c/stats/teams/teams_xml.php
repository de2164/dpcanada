<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'xml.inc');
include_once($relPath.'page_tally.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');
$db_Connection=new dbConnect();

if (empty($_GET['id'])) {
	include_once($relPath.'theme.inc');
	theme("Error!", "header");
	echo "<br><center>A team id must specified in the following format:<br>$code_url/stats/teams/teams_xml.php?id=****</center>";
	theme("", "footer");
	exit();
}

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$result = select_from_teams("id = {$_GET['id']}");
$curTeam = mysql_fetch_assoc($result);

$team_id = $curTeam['id'];

//Team info portion of $data

	$result = mysql_query("SELECT COUNT(id) AS totalTeams FROM user_teams");
	$totalTeams = mysql_result($result, 0, "totalTeams");

	$data = "<teaminfo id='$team_id'>
			<teamname>".xmlencode($curTeam['teamname'])."</teamname>
			<datecreated>".date("m/d/Y", $curTeam['created'])."</datecreated>
			<leader>".xmlencode($curTeam['createdby'])."</leader>
			<description>".xmlencode($curTeam['team_info'])."</description>
			<website>".xmlencode($curTeam['webpage'])."</website>
			<forums>".xmlencode($GLOBALS['forums_url']."/viewtopic.php?t=".$curTeam['topic_id'])."</forums>
			<totalmembers>".$curTeam['member_count']."</totalmembers>
			<currentmembers>".$curTeam['active_members']."</currentmembers>
			<retiredmembers>".($curTeam['member_count'] - $curTeam['active_members'])."</retiredmembers>";

	foreach ( $page_tally_names as $tally_name => $tally_title )
	{
		$teams_tallyboard = new TallyBoard( $tally_name, 'T' );

		$pageCount = $teams_tallyboard->get_current_tally( $team_id );
		$pageCountRank = $teams_tallyboard->get_rank( $team_id );

		$avg_pages_per_day = get_daily_average( $curTeam['created'], $pageCount );

		list($bestDayCount, $bestDayTimestamp) =
			$teams_tallyboard->get_info_re_largest_delta( $team_id );
		$bestDayTime = date("M. jS, Y", ($bestDayTimestamp-1));

		$data .= "
			<roundinfo id='$tally_name'>
				<totalpages>$pageCount</totalpages>
				<rank>".$pageCountRank."/".$totalTeams."</rank>
				<avgpagesday>".number_format($avg_pages_per_day,1)."</avgpagesday>
				<mostpagesday>".$bestDayCount." (".$bestDayTime.")</mostpagesday>
			</roundinfo>";
	}

	$data .= "
		</teaminfo>
	";

//Team members portion of $data
	$data .= "<teammembers>";
	$mbrQuery = mysql_query("
		SELECT username, date_created, u_id, u_privacy
		FROM users
		WHERE $team_id IN (team_1, team_2, team_3)
		ORDER BY username ASC
	");
	while ($curMbr = mysql_fetch_assoc($mbrQuery))
	{
		if ($curMbr['u_privacy'] == PRIVACY_PUBLIC)
		{
			$data .= "<member id=\"".$curMbr['u_id']."\">
				<username>".xmlencode($curMbr['username'])."</username>
				<datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
			</member>
			";
		}
	}
	$data .= "</teammembers>";


$xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
<teamstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"teamstats.xsd\">
$data
</teamstats>";

echo $xmlpage;
?>
