<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');

$query="select user_project_info.projectid, state from user_project_info, projects where user_project_info.projectid=projects.projectid and user_project_info.username='keckrich' and t_latest_page_event > 0";
$res1=mysql_query($query);

while ( list($projectid, $state) = mysql_fetch_row($res1) ) {
    $query="select count(*), sum(round4_user = 'keckrich') from $projectid";
    $res2=mysql_query($query);
    if($res2) {
        list($total_pages, $k_pages) = mysql_fetch_row($res2);
        if($k_pages == 0 || $state != 'F2.proj_unavail')
            continue;
        echo "$state - <a href='http://www.pgdp.net/c/tools/project_manager/page_detail.php?project=$projectid'>$projectid</a> - $total_pages - $k_pages (" . (($k_pages / $total_pages) * 100) . "%)<br>";

        $total_projects++;

        if($k_pages == $total_pages) {
            echo "<blockquote>100% pages done, not analyzing diffs</blockquote>";
            continue;
	}

        // now look for diffs
        $query="select image, round3_text, round4_text from $projectid where round4_user = 'keckrich'";
        $res3=mysql_query($query);
        echo "<blockquote>";
        echo "<table border=1>";
        while( list($image, $P3, $F1) = mysql_fetch_row($res3)) {
            // remove tags from both for easier comparison
            $P3 = strip_tags($P3);
            $F1 = strip_tags($F1);

            // if the texts are identical don't look at them
            if($P3 == $F1)
                continue;
            // if the P3 text doesn't include proofers notes, don't look at them
            if(strpos($P3,"[**")===FALSE)
                continue;
            echo "<tr><td><a href='http://www.pgdp.net/c/tools/project_manager/diff.php?project=$projectid&image=$image&L_round_num=3&R_round_num=4'>$image</a></td><td> " .nl2br($P3) . "</td><td>" . nl2br($F1) . "</td></tr>";
        }
        echo "</table>";
        echo "</blockquote>";
    }
}

echo "Total projects: $total_projects";

?>
