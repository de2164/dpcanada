<?PHP
$relPath='../c/pinc/';
include_once($relPath.'dp_main.inc');

if (!user_is_a_sitemanager()) die("permission denied");

phpinfo();

// vim: sw=4 ts=4 expandtab
?>
