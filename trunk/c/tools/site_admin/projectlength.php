<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

function echo_result($result) {
  ?><table><tr><?
  if(! $result) { ?><th>result not valid</th><? }
  else {
    $i = 0;
    while ($i < mysql_num_fields($result)) {
      $meta = mysql_fetch_field($result, $i);
      ?><th style="white-space:nowrap"><?=$meta->name?></th><?
      $i++;
    }
    ?></tr><?
   
    if(mysql_num_rows($result) == 0) {
      ?><tr><td colspan="<?=mysql_num_fields($result)?>">
      <strong><center>no result</center></strong>
      </td></tr><?
    } else
      while($row=mysql_fetch_assoc($result)) {
        ?><tr style="white-space:nowrap"><?
        foreach($row as $key=>$value) { ?><td><?=$value?></td><? }
        ?></tr><?
      }
  }
  ?></table><?
}

theme("Project Timing", "header");

$query = 'SELECT projects.projectid AS \'ID\', projects.nameofwork AS \'Name\', projects.authorsname AS \'Author\', projects.username AS \'User\', projects.state AS \'State\', projects.postednum AS \'PostedPG\', FROM_UNIXTIME(project_events.timestamp) AS \'Start\', FROM_UNIXTIME(projects.modifieddate) AS \'Last Modified\', '
        . ' DATEDIFF(FROM_UNIXTIME(projects.modifieddate), FROM_UNIXTIME(project_events.timestamp)) AS \'Mod Days\','
        . ' FROM_UNIXTIME(projects.t_last_edit) AS \'Last Edit\', '
        . ' DATEDIFF(FROM_UNIXTIME(projects.t_last_edit), FROM_UNIXTIME(project_events.timestamp)) AS \'Edit Days\''
        . ' FROM projects'
        . ' LEFT JOIN project_events ON projects.projectid = project_events.projectid'
        . ' WHERE project_events.event_type = "creation"'
        . ' ORDER BY projects.postednum, project_events.timestamp';

$result = mysql_query($query);

echo_result($result);

theme("","footer");
?>
