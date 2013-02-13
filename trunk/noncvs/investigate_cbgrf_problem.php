<?PHP

$relPath='../c/pinc/';
include_once('cli.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$pm_name = 'cbgrf';
$round_id = 'P2';

// Consider projects whose PM is $pm_name
// and look at all of their transitions to/from available in $round_id.

$res = mysql_query("
	SELECT *
	FROM project_events JOIN projects USING (projectid)
	WHERE
		username='$pm_name'
		AND
		event_type='transition'
		AND
		(details1='$round_id.proj_avail' OR details2='$round_id.proj_avail')
	ORDER BY event_id;
") or die(mysql_error());

while ( $ob = mysql_fetch_object($res) )
{
	// echo "$ob->timestamp $ob->projectid $ob->details1 $ob->details2 $ob->details3\n";
	// echo "$ob->details1 $ob->details2\n";
	if ( $ob->details2 == "$round_id.proj_avail" )
	{
		// usually from $round_id.proj_waiting
		$ob->sign = +1;
	}
	elseif ( $ob->details1 == "$round_id.proj_avail" )
	{
		// usually to $round_id.proj_done, possibly to $round_id.proj_unavail
		$ob->sign = -1;
	}
	else
	{
		assert(0);
	}
	$events[] = $ob;
}

$count = 0;
foreach ( $events as $ob )
{
	$count += $ob->sign;
}
echo "$count\n";

$curr_projectids = array();
// Were there any projects available at the start of record-keeping?
$projectid_count = array();
foreach ( $events as $ob )
{
	$projectid_count[$ob->projectid] += $ob->sign;
}
foreach ( $projectid_count as $projectid => $count )
{
	// echo "$projectid $count\n";
	assert( $count == -1 or $count == 0 or $count == +1 );
	if ( $count == -1 )
	{
		echo "at start: $projectid\n";
		$curr_projectids[$projectid] = 1;
	}
}
echo count($curr_projectids), "\n";

$time_when_pm_slot_opened = 0;
$t_latest_departure = 0;
foreach ( $events as $ob )
{
	$t = strftime("%Y-%m-%d %T", $ob->timestamp);
	echo "$ob->sign $t $ob->projectid $ob->who $ob->details1 $ob->details2 $ob->details3\n";
	if ( $ob->sign == +1 )
	{
		// Add this project to the available set.
		assert( !array_key_exists($ob->projectid, $curr_projectids) );
		$curr_projectids[$ob->projectid] = 1;

		if ( $ob->who == '[AUTO]' and isset($time_when_pm_slot_opened) )
		{
			echo "time since pm slot opened: ", $ob->timestamp - $time_when_pm_slot_opened, "\n";
			// echo "time since latest departure: ", $ob->timestamp - $t_latest_departure, "\n";
		}
		else
		{
			echo "pushed\n";
		}
		if ( count($curr_projectids) == 11 ) unset($time_when_pm_slot_opened);
	
	}
	elseif ( $ob->sign == -1 )
	{
		// Remove this project
		assert( array_key_exists($ob->projectid, $curr_projectids) );
		unset($curr_projectids[$ob->projectid]);

		if ( count($curr_projectids) == 10 ) $time_when_pm_slot_opened = $ob->timestamp;
		$t_latest_departure = $ob->timestamp;
	}
	else
	{
		assert(0);
	}
	echo $ob->sign, " to ", count($curr_projectids), "\n";
}

echo implode("\n", array_keys($curr_projectids)), "\n";

// vim: sw=4 ts=4 expandtab
?>
