<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');

if (!user_is_a_sitemanager() && $pguser != 'donovan') die("permission denied");

header('Content-type: text/plain');
passthru('df -h');

// vim: sw=4 ts=4 expandtab
?>
