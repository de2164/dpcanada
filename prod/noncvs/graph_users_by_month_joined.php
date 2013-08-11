<?
$relPath="../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');

include_once($relPath.'connect.inc');
new dbConnect();

$which_graph = @$_GET['which_graph'];

$n_days_ago = 28;

///////////////////////////////////////////////////
// For each month in which someone joined,
// get the number who joined,
// and the number of those who have been active in the last $n_days_ago days.
//
$result = mysql_query("
    SELECT
        FROM_UNIXTIME(date_created, '%Y-%m')
            AS month,
        COUNT(*)
            AS num_who_joined,
        SUM((unix_timestamp() - t_last_activity) < $n_days_ago * 86400 )
            AS num_active
    FROM users
    GROUP BY month
    -- HAVING month >= '2002-12' -- '2003-08'
    ORDER BY month
");

// If there was a month when nobody joined,
// then the results will not include a row for that month.
// This may lead to a misleading graph,
// depending on its style.

while ( $row = mysql_fetch_object($result) )
{
    $datax[]  = $row->month;
    $n_joined_data[] = $row->num_who_joined;
    $n_active_data[] = $row->num_active;
    $percentage_data[] = 100 * $row->num_active / $row->num_who_joined;
}


// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",900);

if ( $which_graph == 'abs_both' )
{
    $graph->SetScale("textlin");

    $graph->yaxis->title->Set('number of users');

    // bar plot: number joined per month
    $n_joined_plot = new BarPlot($n_joined_data);
    $n_joined_plot->SetFillColor("green");
    $n_joined_plot->SetLegend("# joined in that month");

    // bar plot: number recently active
    $n_active_plot = new BarPlot($n_active_data);
    $n_active_plot->SetFillColor("blue");
    $n_active_plot->SetLegend("# of those active in last $n_days_ago days");
     
    $n_joined_and_active_plot = new GroupBarPlot(array($n_joined_plot,$n_active_plot));

    // ...and add it to the graph
    $graph->Add($n_joined_and_active_plot);

    // Setup the title
    $graph->title->Set("Signup and Retention Rates");
}
elseif ( $which_graph == 'abs_active' )
{
    $graph->SetScale("textlin");

    $graph->yaxis->title->Set('number of users');

    // bar plot: number recently active
    $n_active_plot = new BarPlot($n_active_data);
    $n_active_plot->SetFillColor("blue");
    $n_active_plot->SetLegend("# of users (per month of joining) active in last $n_days_ago days");
     
    // ...and add it to the graph
    $graph->Add($n_active_plot);

    // Setup the title
    $graph->title->Set("Retention Rate");
}
elseif ( $which_graph == 'percentage' )
{
    $graph->SetScale("textlin",0,100);

    $graph->yaxis->title->Set("% of joined users active in last $n_days_ago days");

    // Create the line plot
    $percentage_plot = new LinePlot($percentage_data);
    $percentage_plot->SetFillColor("lightseagreen");

    // ...and add it to the graph
    $graph->Add($percentage_plot);

    // Setup the title
    $graph->title->Set("Percentage of Users Active in Last $n_days_ago Days");
}
else
{
    echo "Bad value for which_graph: '$which_graph'<br>\n";
    echo "Expecting 'abs_both', 'abs_active', or 'percentage'\n";
    exit;
}

$graph->yaxis->SetTitleMargin(45);

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("month joined");
$graph->xaxis->SetTitleMargin(50);


//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom

$graph->img->SetMargin(70,30,20,100);

$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

$graph->legend->Pos(0.15,0.1,"left" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();

// vim: sw=4 ts=4 expandtab
?>
