<?
$relPath="../pinc/";
include_once($relPath.'dpinit.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

$order = Arg("order", "default");

$title = _("Books To Be Released");
theme($title,'header');

echo "<br><h2>$title</h2>\n";

if ($order == 'default') {
    $order ='nameofwork';
}

//get projects that have been checked out
$rows = $dpdb->SqlRows("
                SELECT nameofwork, username, modifieddate, language, genre
                 FROM projects
                 WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."'
                 ORDER BY $order ASC");

$numrows = count($rows);

echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>\n";

echo "<tr bgcolor='".$theme['color_headerbar_bg']."'>\n";
echo "<td colspan='6'><center><font color='".$theme['color_headerbar_font']."'><b>$title</b></font></center></td></tr>\n";

echo "<tr bgcolor='".$theme['color_navbar_bg']."'>\n";
echo "<th>"._("Index")."</th>
      <th>"._("Name of Work")."</th>
      <th><a href =\"to_be_released.php?order=username\">"._("Project Manager")."</th>
      <th><a href =\"to_be_released.php?order=modifieddate\">"._("Date Last Modified")."</a></th>
      <th>"._("Language")."</b></th>
      <th>"._("Genre")."</b></th>
      </tr>";

$rownum = 0;
foreach($rows as $row) {
    $nameofwork = $row["nameofwork"];
    $username = $row["username"];
    $modifieddate = $row["modifieddate"];
    $language = $row["language"];
    $genre = $row["genre"];

    $today = getdate($modifieddate);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $datestamp = "$month $mday,$year";
    $rownum++;

    echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
    echo "<td>$rownum</td>
          <td width=\"200\">$nameofwork</td><td>$username</td><td>$datestamp</td><td>$language</td><td>$genre</td>
          </tr>\n";
}

echo "</table>";
theme("","footer");
?>


