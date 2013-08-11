<?php
$relPath='./../pinc/';
include($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include($relPath.'connect.inc');
include('statestats.inc');
include_once($relPath.'gettext_setup.inc');
$db_Connection=new dbConnect();


// Not translating this file since it is not used by end-users



// display project count progress - here for the moment, can be moved to stats bar later
$cday = date('d'); $cmonth = date('m'); $cyear = date('Y');
$today = date('Y-m-d');

if ($cday != 1) {
    $start_date = $cyear."-".$cmonth."-01";
    $descrip = "so far this month";
} else {
    $descrip = "since the start of last month";
    if ($cmonth != 1) {
	$temp = $cmonth -1;
	$start_date = $cyear."-".$temp."-01";
    } else {
	$temp = $cyear - 1;
 	$start_date = $temp."-12-01";
    }
}


$psd = get_project_status_descriptor('created');
$created = state_change_since ($psd->state_selector, $start_date);



echo "<b>$created</b> projects have been created $descrip<br>";

$psd = get_project_status_descriptor('proofed');
$FinProof = state_change_since ($psd->state_selector, $start_date);



echo "<b>$FinProof</b> projects have finished proofreading $descrip<br>";


$psd = get_project_status_descriptor('PPd');
$FinPP = state_change_since ($psd->state_selector, $start_date);



echo "<b>$FinPP</b> projects have finished PPing $descrip<br>";



// ****************************************

echo "<br><br>";
$descrip = "in November";
$psd = get_project_status_descriptor('created');
$created = state_change_between_dates($psd->state_selector, '2003-11-01','2003-12-01');
echo "<b>$created</b> projects were created $descrip<br>";

$psd = get_project_status_descriptor('proofed');
$FinProof = state_change_between_dates($psd->state_selector, '2003-11-01','2003-12-01');
echo "<b>$FinProof</b> projects were proofread $descrip<br>";

$psd = get_project_status_descriptor('PPd');
$FinPP = state_change_between_dates($psd->state_selector, '2003-11-01','2003-12-01');
echo "<b>$FinPP</b> projects were PPd $descrip<br>";


echo "<br><br>";
$descrip = "in October";
$psd = get_project_status_descriptor('created');
$created = state_change_between_dates($psd->state_selector, '2003-10-03','2003-11-01');
$created += 19; // historical adjustment for first days of Oct
echo "<b>$created</b> projects were created $descrip<br>";

$psd = get_project_status_descriptor('proofed');
$FinProof = state_change_between_dates($psd->state_selector, '2003-10-03','2003-11-01');
$FinProof += 69; // historical adjustment for first days of Oct
echo "<b>$FinProof</b> projects were proofread $descrip<br>";

$psd = get_project_status_descriptor('PPd');
$FinPP = state_change_between_dates($psd->state_selector, '2003-10-03','2003-11-01');
$FinPP +=   28; // historical adjustment for first days of Oct
echo "<b>$FinPP</b> projects were PPd $descrip<br>";




?>
