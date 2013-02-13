<?php
$path = '../jpgraph/src/';
/*
include ("$path/jpgraph.php");
include ("$path/jpgraph_pie.php");
include ("$path/jpgraph_pie3d.php");
*/
$relPath = '../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');

if (isset($_GET['img']))
{
    $d = $_GET['d'];

    if ($d == 0)
    {
        $graph = init_pie_graph( 660, 400, 5 );

        $title = "Net pages saved so far today";
    
        for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
        {
            $round = get_Round_for_round_number( $rn );
            $site_stats = get_site_page_tally_summary($round->id);
            $data[] = $pages = $site_stats->curr_day_actual;
            $labels[] = "$round->id ($pages)";
        }
    }
    else 
    {
        $graph = init_pie_graph( 660, 400, 60 );

        $title = "Net pages saved in preceding $d days";

        for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
        {
            $round = get_Round_for_round_number( $rn );
            $tallyboard = new TallyBoard($round->id , 'S');
            $now = time();
            $data[] = $pages = $tallyboard->get_delta_sum(1, $now - (60*60*24*$_GET['d']),$now);
            $labels[] = "$round->id ($pages)";
        }
    }

    draw_pie_graph( $graph, $labels, $data, $title );

/*
    $graph = new PieGraph(660,400,"auto");
    $graph->SetShadow();

    $graph->title->Set($title);
    $graph->title->SetFont(FF_FONT1,FS_BOLD);

    $p1 = new PiePlot3D($data);
    $p1->ExplodeAll();
    $p1->SetCenter(0.45);
    $p1->SetLegends($labels);

    $graph->Add($p1);
    $graph->Stroke();
*/

    die;
}

else 
{

echo <<<HTML
<h1>Pie</h1>
<p style="font-size: small;">Only "today" is real-time; others updated at stats run time.</p>
<img src='?img&d=0' /><br /><br />
<img src='?img&d=1' /><br /><br />
<img src='?img&d=7' /><br /><br />
<img src='?img&d=28' /><br /><br />
<img src='?img&d=180' /><br /><br />

HTML;

}

?>
