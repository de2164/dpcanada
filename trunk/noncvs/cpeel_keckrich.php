<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');

echo "state - projectID - total_pages - pages_by_k (% by k)<br>";

$query="select user_project_info.projectid, state from user_project_info, projects where user_project_info.projectid=projects.projectid and user_project_info.username='keckrich' and t_latest_page_event > 0";
$res1=mysql_query($query);

while ( list($projectid, $state) = mysql_fetch_row($res1) ) {
    $query="select count(*), sum(round4_user = 'keckrich') from $projectid";
    $res2=mysql_query($query);
    if($res2) {
        list($total_pages, $k_pages) = mysql_fetch_row($res2);
        if($k_pages == 0) # || $state != 'F2.proj_unavail')
            continue;
        echo "$state - <a href='http://www.pgdp.net/c/tools/project_manager/page_detail.php?project=$projectid'>$projectid</a> - $total_pages - $k_pages (" . (($k_pages / $total_pages) * 100) . "%)<br>";

        $total_projects++;
    }
}

echo "Total projects: $total_projects";

?>
