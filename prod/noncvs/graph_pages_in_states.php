<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');

$jpgraph_path = '../jpgraph/src/';
include ("$jpgraph_path/jpgraph.php");
include ("$jpgraph_path/jpgraph_bar.php");

$n_pages_ = array();
$n_aailable_pages_ = array();
$res = mysql_query("
    SELECT state, SUM(n_pages), SUM(n_available_pages)
    FROM projects
    WHERE state != 'proj_submit_pgposted' AND state != 'project_delete'
    GROUP BY state
") or die(mysql_error());
while (list($state,$sum_n_pages,$sum_n_available_pages) = mysql_fetch_row($res) )
{
    $n_pages_[$state] = $sum_n_pages;
    $n_available_pages_[$state] = $sum_n_available_pages;
}

$stage_labels = array();
$unavail_n_pages=array();
$waiting_n_pages=array();
$available_n_pages=array();
$progordone_n_pages=array();

// ---------------

$stage_labels[] = 'New';
$unavail_n_pages[] = $n_pages_[PROJ_NEW];
$waiting_n_pages[] = 0;
$available_n_pages[] = 0;
$progordone_n_pages[] = 0;

if ($site_supports_metadata)
{
    // blah
}

foreach ( $Round_for_round_id_ as $round )
{
    $stage_labels[] = $round->id;
    $unavail_n_pages[] = $n_pages_[$round->project_unavailable_state] + $n_pages_[$round->project_bad_state];
    $waiting_n_pages[] = $n_pages_[$round->project_waiting_state];
    $available_n_pages[] = $n_available_pages_[$round->project_available_state];
    $progordone_n_pages[] =
        $n_pages_[$round->project_available_state]
        - $n_available_pages_[$round->project_available_state]
        + $n_pages[$round->project_complete_state];
}

$stage_labels[] = 'PP';
$unavail_n_pages[] = $n_pages_[PROJ_POST_FIRST_UNAVAILABLE];
$waiting_n_pages[] = 0;
$available_n_pages[] = $n_pages_[PROJ_POST_FIRST_AVAILABLE];
$progordone_n_pages[] = $n_pages_[PROJ_POST_FIRST_CHECKED_OUT];


$stage_labels[] = 'PPV';
$unavail_n_pages[] = 0;
$waiting_n_pages[] = 0;
$available_n_pages[] = $n_pages_[PROJ_POST_SECOND_AVAILABLE];
$progordone_n_pages[] = $n_pages_[PROJ_POST_SECOND_CHECKED_OUT];

// ---------------

// Create the graph. These two calls are always required
$graph = new Graph(800,500,"auto");    
$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(50,230,30,40);

// ------------------------

// Create the bar plots
$unavail_plot = new BarPlot($unavail_n_pages);
$unavail_plot->SetFillColor("lightslateblue");
$unavail_plot->SetLegend('unavailable');

$waiting_plot = new BarPlot($waiting_n_pages);
$waiting_plot->SetFillColor("maroon");
$waiting_plot->SetLegend('waiting (to be available)');

$available_plot = new BarPlot($available_n_pages);
$available_plot->SetFillColor("lightyellow");
$available_plot->SetLegend('available');

$progordone_plot = new BarPlot($progordone_n_pages);
$progordone_plot->SetFillColor("cadetblue1");
$progordone_plot->SetLegend('in progress or done');

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($unavail_plot,$waiting_plot,$available_plot,$progordone_plot));

// ...and add it to the graPH
$graph->Add($gbplot);

// ------------------------

$graph->title->Set("Number of pages in various states");
$graph->xaxis->title->Set("stage");
// $graph->yaxis->title->Set("Number of Pages");

$graph->xaxis->SetTickLabels($stage_labels);

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();

// vim: sw=4 ts=4 expandtab
?>
