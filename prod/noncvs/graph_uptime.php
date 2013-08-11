<?PHP
$relPath='../c/pinc/';
include_once($relPath.'site_vars.php');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');

$uptime_log_path = '/home/stacy/uptime-log';

$data_x = array();
$data_y = array();

$prev_H = NULL;

$lines = file($uptime_log_path);
for ( $i = count($lines)-1; $i >= 0; $i-- )
{
    $line = $lines[$i];
    $n = preg_match(
        '/^ (\d\d):(\d\d):(\d\d) up \d+ days,\s+(?:\d+:\d\d|\d+ min),\s+\d+ users?,  load average: ([\d.]+), ([\d.]+), ([\d.]+)$/',
        $line,
        $matches
    );
    if ( $n == 0 )
    {
        echo $line;
        assert( FALSE );
    }
    list($_, $H, $M, $S, $LA1, $LA2, $LA3) = $matches;

    if ( is_null($prev_H) )
    {
        // Assume that the latest line in the log file refers to today.
        $now_assoc = getdate();
        $now_y = $now_assoc['year'];
        $now_m = $now_assoc['mon'];
        $now_d = $now_assoc['mday'];
        $curr_day_offset = 0;
    }
    else
    {
        if ( $H <= $prev_H )
        {
            // the usual case, because we're going backward through the file
        }
        else
        {
            // went over a day-boundary
            $curr_day_offset--;
        }
    }
    // echo "mktime( $H, $M, $S, $now_m, $now_d+$curr_day_offset, $now_y, TRUE );\n";
    $timestamp = mktime( $H, $M, $S, $now_m, $now_d+$curr_day_offset, $now_y, TRUE );
    // echo $timestamp, " ", date('r', $timestamp), " ", $LA3, "<br>\n";

    $data_x[] = $timestamp;
    $data_y[] = $LA3;
    
    $prev_H = $H;
    // if (count($data_x) == 100) break;
    if ( $data_x[0] - $timestamp > 86400*14 ) break;
}

// Setup the basic graph
$graph = new Graph(700,500);
$graph->SetMargin(40,40,30,80);    
$graph->title->Set('load average over time');

$start = mktime(0,0,0, $now_m,$now_d+$curr_day_offset,$now_y, 1);
$graph->SetScale("intlin",0,18,$start,$data_x[0]);

// Setup the x-axis with a format callback to convert the timestamp
// to a user readable time
$graph->xscale->ticks->Set(86400*1,86400);
$graph->xaxis->SetLabelAngle(90);
$graph->xgrid->Show(true);
$graph->xaxis->SetLabelFormatCallback('TimeCallback');

// Create the line
$p1 = new LinePlot($data_y,$data_x);
$p1->SetColor("blue");

// Set the fill color partly transparent
$p1->SetFillColor("blue@0.4");

// Add lineplot to the graph
$graph->Add($p1);

// Output line
$graph->Stroke();

// The callback that converts timestamp to minutes and seconds
function TimeCallback($aVal) {
    return Date('m-d H:i',$aVal);
}


// vim: sw=4 ts=4 expandtab
?>
