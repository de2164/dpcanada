<?
ini_set('display_errors', 1);
error_reporting(E_ALL);

$relPath = "../c/pinc/";
include_once $relPath . 'dpinit.php';

$rows = $dpdb->SqlRows("
    SELECT projectid, phase FROM projects
    WHERE phase IN ('P1', 'P2', 'P3', 'F1', 'F2')
    ");

foreach($rows as $row) {
    $n = $dpdb->SqlOneValue("
        SELECT COUNT(1) FROM {$row['projectid']}
        WHERE state LIKE '%page_temp'");
    if($n > 0) {
        dump("{$row['projectid']} {$row['phase']} $n");
    }
}

