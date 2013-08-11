<?PHP

function get_least_event_id_with_timestamp( $start_timestamp )
{
	static $min_event_id, $min_timestamp;
	static $max_event_id, $max_timestamp;

    if ( is_null($min_event_id) )
    {
        $res = mysql_query("
            SELECT event_id, timestamp
            FROM page_events
            ORDER BY event_id ASC
            LIMIT 1
        ") or die(mysql_error());
        list($min_event_id, $min_timestamp) = mysql_fetch_row($res);

        $res = mysql_query("
            SELECT event_id, timestamp
            FROM page_events
            ORDER BY event_id DESC
            LIMIT 1
        ") or die(mysql_error());
        list($max_event_id, $max_timestamp) = mysql_fetch_row($res);
    }

	$trace = FALSE;

	if ( $trace )
    {
        echo "\n";
        echo "get_least_event_id_with_timestamp( $start_timestamp )\n";
    }

	$target_timestamp = $start_timestamp - 0.5;

    $lo_event_id = $min_event_id; $lo_timestamp = $min_timestamp;
    $hi_event_id = $max_event_id; $hi_timestamp = $max_timestamp;

	for ( $step = 1; $step <= 20; $step++ )
	{
		if ( $trace )
		{
			echo "\n";
			echo "$lo_event_id, $lo_timestamp\n";
			echo "$hi_event_id, $hi_timestamp\n";
			echo "(", $hi_event_id - $lo_event_id, ", ", $hi_timestamp - $lo_timestamp, ")\n";
		}

		// linear interpolation:
		$interp_event_id = $lo_event_id + ($target_timestamp - $lo_timestamp)*($hi_event_id - $lo_event_id)/($hi_timestamp - $lo_timestamp);
		if ( $trace ) echo "$interp_event_id\n";

		$lo = floor($interp_event_id - 5);
		$hi = ceil($interp_event_id + 5);

		$events = array();
		$res = mysql_query("
			SELECT event_id, timestamp
			FROM page_events
			WHERE event_id BETWEEN $lo AND $hi
		") or die(mysql_error());
		while ( list($event_id, $timestamp) = mysql_fetch_row($res) )
		{
			if ( $trace ) echo "    $event_id, $timestamp\n";
			$events[] = array($event_id,$timestamp);
		}

		$i = 0;
		if ( $target_timestamp < $events[$i][1] )
		{
			if ( $trace ) echo "look earlier\n";
			list($hi_event_id, $hi_timestamp) = $events[$i];
			continue;
		}

		$k = count($events)-1;
		if ( $target_timestamp > $events[$k][1] )
		{
			if ( $trace ) echo "look later\n";
			list($lo_event_id, $lo_timestamp) = $events[$k];
			continue;
		}

		if ( $trace ) echo "found after $step steps!\n";
		for ( $i = 0; $i <= count($events)-2; $i++ )
		{
			$this_event = $events[$i];
			$next_event = $events[$i+1];
			if ( $this_event[1] < $target_timestamp and $target_timestamp < $next_event[1] )
			{
				if ( $trace )
				{
					echo "between:\n";
					echo "   ", $this_event[0], ", ", $this_event[1], "\n";
					echo "   ", $next_event[0], ", ", $next_event[1], "\n";
				}
				return $next_event[0];
			}
		}
		assert(FALSE);
	}
    die( "get_least_event_id_with_timestamp( $start_timestamp ): did not converge" );
}

// vim: sw=4 ts=4 expandtab
?>
