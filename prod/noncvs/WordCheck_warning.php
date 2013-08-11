<?php
$info_url = "http://www.pgdp.net/phpBB2/viewtopic.php?p=326010#326010";
$please_see = "Please see <a href='$info_url'>here</a> for more information.";

list($year,$month,$day,$hour,$minute,$second,$is_dst) = array(2007,05,23, 8,00,00, 1);
$then_sse = mktime($hour,$minute,$second,$month,$day,$year,$is_dst);
$then_str = strftime('%B %e, starting at %H:%M server time', $then_sse);
$now_sse = time();

if ( $now_sse < $then_sse )
{
    $minutes_left = round( ( $then_sse - $now_sse ) / 60 );
    $text = "
        The WordCheck PM interface will be upgraded $then_str (or in roughly $minutes_left minutes).
        Please do not submit word list changes <i>from this page</i> after that time; your changes will be lost.
        Instead, word lists will be edited from their own page.
        $please_see
    ";
}
else
{
    $text = "
        The WordCheck PM interface is currently being upgraded.
        Please do not submit word list changes from this page; your changes will be lost.
        Instead, word lists are now (or will soon be) edited from their own page.
        $please_see
    ";
}

echo "
    <font color='red'>
    <h3>WARNING!</h3>
    $text
    </font>
";

// vim: sw=4 ts=4 expandtab
?>
