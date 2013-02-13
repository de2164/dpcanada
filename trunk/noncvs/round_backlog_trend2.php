<?
$relPath="../c/pinc/";
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');

$weeks = 4;

// Start with creating the Graph, this enables the use of the cache
// where possisble
$width=300;
$height=200;
$cache_timeout=-1; # in minutes
$graph = new Graph($width,$height,"auto",$cache_timeout);

new dbConnect();

// Pull all interested phases, primarily all the rounds and PP
$interested_phases = array_keys($Round_for_round_id_);
$interested_phases[] = 'PP';


// Pull the stats data out of the database
$stats = array();
foreach($interested_phases as $phase)
{
    $where_state = _get_project_state_selector($phase, array("available","waiting"));
    $sql = "SELECT SUM(n_pages) as num_pages
            FROM projects
            WHERE $where_state";
    $res = mysql_query($sql);
    while( $result = mysql_fetch_assoc($res) )
    {
        $stats[$phase]=$result["num_pages"];
    }
    mysql_free_result($res);
}

// Pop off any trailing phases that have no pages.
// This prevents new sites from showing rounds
// that haven't started yet.
$reverse_phases = array_reverse($interested_phases);
foreach($reverse_phases as $phase)
{
    if($stats[$phase] > 0)
        break;
    array_pop($stats);
}

$phases_with_data = array_keys($stats);

// get the total of all phases
$stats_total = array_sum($stats);

// If this is a new system there won't be any stats so don't divide by zero
if($stats_total == 0)
{
    dpgraph_error(_("No pages found."));
}

// Generate the date ranges
$date_ranges = array();
for($days_ago=0; $days_ago<($weeks * 7); $days_ago+=7)
{
    $before = time() - (60 * 60 * 24 * $days_ago);
    $after = time() - (60 * 60 * 24 * ($days_ago + 7));
    $date_ranges[] = array($before, $after);
}

// Need to reverse the date ranges so they go from past to present
$date_ranges = array_reverse($date_ranges);

// Get page completed trend information
$today = time();
$week_ago = $today - (60 * 60 * 24 * $days_ago);
foreach($stats as $phase => $pages)
{
    foreach(array("waiting","available") as $substate)
    {
        $where_state = _get_project_state_selector($phase, $substate);

        foreach($date_ranges as $range)
        {
            list($before, $after) = $range;
            $sql = "SELECT SUM(num_pages)
                    FROM project_state_stats
                    WHERE $where_state AND
                    date >= FROM_UNIXTIME($after) AND date < FROM_UNIXTIME($before)";
            $res = mysql_query($sql);
            list($pages) = mysql_fetch_row($res);
            mysql_free_result($res);

            @$pages_per_days_ago[$before][$substate][$phase]=ceil($pages / 7);
        }

        if($phase == 'PP')
            break;
    }
}

// colors
#$normalBarColors=array("#9999ff","#6666ff","#3333ff","#0000ff");
#$warningBarColors=array("#ff9999","#ff6666","#ff3333","#ff0000");
$normalBarColors=array("#bfbfff","#8080ff","#4040ff","#0000ff");
$warningBarColors=array("#ffbfbf","#ff8080","#ff4040","#ff0000");

// calculate the goal percent as 100 / number_of_phases
$goal_percent = ceil( 100 / count($stats) );

$plots = array();

$barColorIndex=0;
foreach($pages_per_days_ago as $days_ago => $substates)
{
    $subplots = array();
    foreach($substates as $substate => $stats)
    {
        // calculate the percentage of work remaining in each round
        // and the color for each bar
        $barColors = array();
        foreach($stats as $phase => $num_pages)
        {
            $stats_percentage[$phase] = ceil(($num_pages/ $stats_total) * 100);
            if($stats_percentage[$phase] > $goal_percent)
                $barColors[]=$warningBarColors[$barColorIndex];
            else
                $barColors[]=$normalBarColors[$barColorIndex];
        }

        // Create a bar plot for each day
        $plot = new BarPlot(array_values($stats));
        $plot->SetFillColor($barColors);
        $plot->SetLineWeight(0);
        $subplots[]=$plot;
    }
    $plots[] = new AccBarPlot($subplots);

    $barColorIndex++;
}

// Some graph variables
$datax = $phases_with_data;
$title = sprintf(_("Page trend over last %s weeks"), $weeks);
#$y_title = _("pages");
#$x_title = sprintf(_("Trend over %s weeks"), $weeks);

$graph->SetScale("textint");

// Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
// left, right, top, bottom
#$graph->img->SetMargin(50,20,30,60);
$graph->img->SetMargin(50,20,30,30);

// Set title
$graph->title->Set($title);
$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->title->Set($x_title);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetMargin(10);

// Set Y axis
$graph->yaxis->title->Set($y_title);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Create a bar plot
$plot = new GroupBarPlot($plots);
$plot->SetWidth(0.8);
$graph->Add($plot);

// Display the graph
$graph->Stroke();

function _get_project_state_selector( $which, $desired_states = NULL )
// This function returns an SQL selector that can be used in a WHERE clause.
// $which is a word denoting a round ID or a phase (NEW, PP, GB)
{
    global $project_state_phase_;

    if(NULL == $desired_states)
    {
        $desired_states = array("unavailable","waiting","bad","available","complete");
    }
    elseif(!is_array($desired_states))
    {
        $desired_states = array($desired_states);
    }

    $sql = "";

    // see if it's a round
    $round = get_Round_for_round_id($which);
    if($round != NULL)
    {
        $states = array();
        foreach($desired_states as $desired_state)
        {
            $state_variable_name = "project_{$desired_state}_state";
            $states[]=$round->$state_variable_name;
        }
        $sql = "state IN ('" . implode("','", $states) . "')";
        return $sql;
    }

    // it may be a pool or stage so look at the stage's phase
    $states = array();
    foreach($project_state_phase_ as $state => $phase)
    {
        if($phase == $which)
        {
            array_push($states,$state);
        }
    }
    if(count($states))
    {
        $sql = "state IN ('" . implode("','",$states) . "')";
        return $sql;
    }

    die("bad value for 'which': '$which'");
}

// vim: sw=4 ts=4 expandtab
?>
