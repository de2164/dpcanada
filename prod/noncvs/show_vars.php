<?PHP
// There are numerous ways in which a project can manifest:
// -- a row in the 'projects' table,
// -- a page-table in the database, 
// -- a project directory in the filesystem,
// -- a discussion topic in the forums.
// -- a mention in marc_records.projectid, page_events.projectid, project_events.projectid, project_pages.projectid
// This script considers the first three.
// Finds all such manifestations,
// and categorizes projects according to what kind of manifestations they have.

error_reporting(E_ALL);

$relPath='../c/pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
new dbConnect;

// A row in the 'projects' table.
$res = mysql_query("
create table stacytest (id int(5) not null default '0', name varchar(10) not null default '');");




#    SHOW variables like '%collat%';
#
#") or die(mysql_error());
#
#while ($row = mysql_fetch_array($res)) {
#   printf("ID: %s  Name: %s", $row[0], $row[1]); 
#}
#
#mysql_free_result($res);
#
