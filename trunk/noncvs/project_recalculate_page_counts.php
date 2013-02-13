<?PHP
$relPath='../c/pinc/';
include_once('./cli.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'connect.inc');
new dbConnect;

$projectid = 'projectID45d3397e662af';

project_recalculate_page_counts($projectid);

?>
