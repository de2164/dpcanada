<?php
$relPath="../c/pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'pg.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'maintenance_mode.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_specific.inc');
$db_Connection=new dbConnect();
include_once($relPath.'showstartexts.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'site_news.inc');

abort_if_in_maintenance_mode();
theme(_("Welcome, Shipmates!"), "header");
$etext_limit = 20;


default_page_heading();

// Show the number of users that have been active over various recent timescales.
foreach ( array(1,7,28) as $days_back )
{
    $res = mysql_query("
        SELECT COUNT(*)
        FROM users
        WHERE t_last_activity > UNIX_TIMESTAMP() - $days_back * 24*60*60
    ") or die(mysql_error());
    $num_users = mysql_result($res,0);
    
    $template = (
        $days_back == 1
        ? _('%s crewmates aboard in the past twenty-four hours.')
        : _('%s crewmates aboard in the past %d days.')
    );
    $msg = sprintf( $template, number_format($num_users), $days_back );
    echo "<center><i><b>$msg</b></i></center>\n";
}

?>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Ahoy There: About This Site") ?></b></font><br>
<?
echo sprintf( _("Distributed Proofreaders be founded in 2000 by Cap'n Charles 'Squirrel King' Franks to support th' digitisation o' Public Domain book-booty. Originally chartered to assist <a href='%s' target='_new'>Project Gut'nberg</a> (PG), Distributed Proofateers (DP) be now th' main source of PG e-books. In 2002, Distributed Privateers received their letter of marque from Project Gut'nberg and as such be supported by Project Gut'nberg. All our proofreaders, managers, developers, deckhands and so on be volunteers."), $PG_home_url );
echo "\n";
echo sprintf( _("If ye be 'avin' any questions or comments regardin' this site, please send a carrier-parrot t' <a href='mailto:%s'>%s</a>."), $general_help_email_addr, $general_help_email_addr );
echo "<br><br>";

/* disable site news
show_news_for_page("FRONT");
and instead put in a hardcoded image
2005: Pirate_Main_A.jpg
2006: Salty_Dog_01.jpg
*/
?>
<br><br><center><img src='<?=$dyn_url?>/dp-images/Salty_Dog_01.jpg' /></center><br><br>
</p>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Here be th' Site Concept"); ?></b></font><br>
<? echo _("This 'ere site provides a web-based method o' easin' th' proofreadin' work associated wi' th' digitization o' Public Domain books into Project Gut'nberg e-books. By divvying up th' work into individual pages many fine, feisty swashbucklers can be attacking th' same book at th' same time. This significantly speeds up th' proofreading/e-book booty-creation process."); ?></p>
<p><? echo _("When a proofreader chooses t' be proofreadin' a page o' a particular book, th' text and image file are displayed on a single web page. This 'ere ploy allows th' page text t' be easily reviewed an' compared t' th' image file, thus assistin' th' proofreadin' o' th' page text. Th' edited text is then submitted back t' th' site via th' same web page that 'twas edited on. A second proofreader is then presented wi' th' work o' th' first proofreader and th' page image. Once they have verified th' work o' th' first proofreader and corrected any additional errors th' page text is again submitted back t' th' site. Th' book then sails thro' two formatting rounds usin' th' same web interface."); ?></p>
<p><? echo _("Once all pages fer a partic'lar book have been processed, a post-processor joins th' pieces, properly formats 'em into a Project Gut'nberg e-book an' submits it t' th' Project Gut'nberg archive.  Arrr!"); ?></p>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Landlubbers: How Ye Can Join th' Crew"); ?></b></font><br>
<? echo sprintf(_("Th' first step t' take t' help us out would be t' <a href='$code_url/accounts/addproofer.php'>register</a> t' be a new proofreader. (A 'Register' link also appears at th' top o' th' screen.)  After ye register be sure t' read over both th' treasure map email ye receive as well as  <a href='%s/faq/faq_central.php'>FAQ Central</a> which provides helpful resources on how t' proofread.  (See also th' 'Help' at th' top o' any screen.)  After ye have registered &amp; read thro' some o' th' intro documents choose a swag-filled book from our Current Projects and try proofreadin' a page or two."),$code_url,$code_url); ?></p>

<p><? echo _("Ye don't even have t' register t' have a look at th' <a href='$code_url/tools/post_proofers/smooth_reading.php'>Smooth Readin' Pool Preview</a>, though ye do t' upload corrections. Follow th' link fer more information."); ?></p>

<p><? echo _("Mind ye well, there be no commitment expected on this site. Proofread as often or as seldom as ye like, and as many or as few pages as ye like.  We encourage pirates everywhere t' do 'a page a day', but it's entirely up t' ye! We hope ye will join us in our mission of 'preserving the literary history o' th' world (and th' world's pirates) in a freely available form fer everyone t' use'."); ?></p>

<font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Plunder!"); ?></b></font><br>
<?
//Gold E-texts
show_special_star_texts($etext_limit,'gold');
//Silver E-texts
show_special_star_texts($etext_limit,'silver');
//Bronze E-texts
show_special_star_texts($etext_limit,'bronze');
theme("", "footer");

function show_special_star_texts($etext_limit,$type)
{
    global $code_url;

    if ($type == "bronze") {
        $state = SQL_CONDITION_BRONZE;
        $text = " " . _("Now Proofreadin'.") . "</font>  " . _("These fine documents be currently sailin' thro' our site; sign in and lend an oar!!!") . " <br>";
    } elseif ($type == "silver") {
        $state = SQL_CONDITION_SILVER;
        $text = " " . _("I' Progress.") . "</font>  " . _("These grand, digitised parchments ha' been divvied up at our site but ha' not yet posted t' Project Gut'nberg (currently goin' thro' their final spitpolish).") . " <br>";
    } elseif ($type == "gold") {
        $state = SQL_CONDITION_GOLD;
        $text = " " . _("Hung from th' Yardarm!") . "</font>  " . _("These treasure maps ha' been sealed in oak chests at our site an' buried i' th' Project Gut'nberg beach.");
        $text .= "
            <a href='$code_url/feeds/backend.php?content=posted'>
                <img src='$code_url/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle' alt='[XML]'></a>
            <a href='$code_url/feeds/backend.php?content=posted&amp;type=rss'>
                <img src='$code_url/graphics/rss.gif' border='0' width='36' height='14' style='vertical-align:middle' alt='[RSS]'></a>
            <br>";
    }

    // $extraWHERE = " AND ( special_code = 'TalkLikeAPirateDay'  OR nameofwork like '%pira%' ) AND nameofwork not like '%conspiracy%' ";
    $extraWHERE = " AND ( special_code = 'TalkLikeAPirateDay' OR nameofwork regexp '[[:<:]]pira(te|ti|cy)' )";

    $total = mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE $state  $extraWHERE"));

    echo "<img src='graphics/{$type}_star.jpg' border='0' height='38' width='40' alt='$type star'> = <font face='Verdana' size='4'>".number_format($total)." $text";

    list_projects( $state.$extraWHERE , "ORDER BY modifieddate DESC", "LIMIT $etext_limit" );

    if ($total > $etext_limit) {
        echo "<font face='Verdana' size='1'>--<a href='$code_url/list_etexts.php?x=".substr($type,0,1)."&sort=5'>"._("See more...")."</a></font><br><br>";
    }

}

// vim: sw=4 ts=4 expandtab
?>
