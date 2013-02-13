<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'Project.inc');
?>
<html>
<head>
<style type='text/css'>
* {font-family: Verdana;}
table {margin-left:auto; margin-right:auto; text-align:center; width: 500px; }
td a {color:#111; text-decoration: none;}
th {background-color: #8a2be2; color: #fff;}
td {border-bottom:1px dashed gray;}
body { background: /* #eed; */ #555; }
.filler { height: 2px; line-height: 2px; background: #000; margin: 0; padding: 0;}
.filldiv {background-image: url(graphics/rpt_shelf.jpg); color: #fff; 
    font-weight: bold; text-align: center; vertical-align:middle; 
    display:block; width:500px; overflow: hidden;}
</style>
<!--[if lt IE 7]>
<script src="/ie7/ie7-standard-p.js" type="text/javascript">
</script>
<![endif]-->
</head>
<body>

<?

echo "<table cellpadding='0' cellspacing='0'>";

foreach ( $PROJECT_STATES_IN_ORDER as $state)
{

    $sql = "SELECT SUM(n_pages) AS 'x' FROM projects WHERE state = '$state'";

    $result = mysql_query($sql);

    if (mysql_num_rows($result) == 0)
        continue;

    echo "<tr><th>".project_states_text($state)."</th></tr>";
    
    if ($state == 'proj_submit_pgposted')
    {
        echo "<tr><td>(no accurate data available)</td></tr>";
        continue;
    }

    while ($pages = mysql_fetch_object($result))
    {
        if (!empty($pages->x))
        {
            $pf = ($pages->x / 20);
            if (isset($_GET['f']))
                $pf = ($pages->x / $_GET['f']);
//            filler($pf/2);
//            echo "<tr><td>$pages->x pages</td></tr>";
//            filler($pf/2);
             echo "<tr><td><div class='filldiv' style='height: {$pf}px; line-height:{$pf}px;'>";
             echo number_format($pages->x) . " pages</div></td></tr>";
        }
        else 
        {
            echo "<tr class='filler'><td class='filler'> </td></tr>";
        }
    }

}
/*
function filler($pages)
{

    for ($i=1;$i<$pages;$i++)
    {
        echo "<tr class='filler'><td class='filler'>&nbsp;</td</tr>";
    }
}
*/

function filler($pages)
{

    echo "<tr><td><div class='filldiv' style='height: {$pages}px;'> </div></td></tr>";
}
// vim: sw=4 ts=4 expandtab
?>
