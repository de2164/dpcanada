<?
$relPath='./../pinc/';
include($relPath.'dpinit.php');


$sql = "SELECT d.dateval,
               COUNT(DISTINCT urp.username) user_count,
               SUM(page_count) page_count,
               ROUND(SUM(page_count) / COUNT(DISTINCT urp.username))  pgs_per_user
        FROM days d
        LEFT JOIN user_round_pages urp
            ON d.dateval = urp.dateval
        WHERE d.dateval >= DATE_SUB(CURRENT_DATE(), INTERVAL 2 MONTH)
            AND d.dateval < CURRENT_DATE()
        GROUP BY d.dateval
        ORDER BY d.dateval";


$rows = $dpdb->SqlRows($sql);
$a_users = array(array("date", "users"));
$a_pages = array(array("date", "pages"));
$a_rate  = array(array("date", "pgs / user"));

foreach($rows as $row) {
    $a_users[] = array($row["dateval"], (int) $row["user_count"]);
    $a_pages[] = array($row["dateval"], (int) $row["page_count"]);
    $a_rate[]  = array($row["dateval"], (int) $row["pgs_per_user"]);
}
$data_users = json_encode($a_users);
$data_pages = json_encode($a_pages);
$data_rate  = json_encode($a_rate);

theme(_("User Logon Statistics"),'header');

echo "<div id='userchart' class='dpchart'> </div>\n";
echo "<div id='pagechart' class='dpchart'> </div>\n";
echo "<div id='ratechart' class='dpchart'> </div>\n";

$utitle = _("Users Per Day");
$ptitle = _("Pages Per Day");
$rtitle = _("Pages Per User");

makeColumnChart($data_users, $utitle, "userchart");
makeColumnChart($data_pages, $ptitle, "pagechart");
makeColumnChart($data_rate,  $rtitle, "ratechart");

theme('','footer');
exit;


function makeColumnChart($data, $caption, $div) {

    echo "
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
  google.load('visualization', '1', {packages:['corechart']});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    // var data = new google.visualization.DataTable();
    var data = new google.visualization.arrayToDataTable(
        {$data}
    );
    var options = {
        baseline: 0,
        title: '$caption',
        width: 900,
        height: 450,
        legend: {position: 'none'},
        vAxes: [
            { title: 'Users', maxValue: '100'},
            { title: 'pages'},
            { title: 'pgs per user', maxValue: '100'}
        ]
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('$div'));
    chart.draw(data, options);
}
</script>\n";
}
?>


vAxes="[{title:'Percent',
format:'#,###%',
titleTextStyle: {color: 'blue'},
textStyle:{color: 'blue'},
textPosition: 'out'},
{title:'Millions',
format:'#,###',
titleTextStyle: {color: 'red'},
textStyle:{color: 'red'},
textPosition: 'out'}]",
hAxes="[{title:'Date',
textPosition: 'out'}]",
width=550, height=500
)