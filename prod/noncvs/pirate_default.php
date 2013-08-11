<?php
$relPath="./pinc/";
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
theme(_("Ahoy, Shipmates!"), "header");
$etext_limit = 10;

default_page_heading();

/* disable site news
show_news_for_page("FRONT");
and instead put in a hardcoded image
2005: Pirate_Main_A.jpg
2006: Salty_Dog_01.jpg
2007: TBD
*/
?>

<center><img src='<?=$dyn_url?>/dp-images/Salty_Dog_01.jpg' /></center>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Here be th' Site Concept"); ?></b></font><br><br>
<? echo _("Distributed Proofreaders be providin' a web-based method t' ease th' conversion o' Publick Domain books int' e-books. By divvyin' up th' workload int' individual pages, a grand great gang o' mateys c'n work on a book at th' same time, puttin' a fair wind t' th' sails o' the creation process."); ?></p>

<p><? echo _("Durin' proofreadin', mateys be presented with a scanned page image and th' correspondin' OCR text on a sin'le web page, by which manoeuvre th' text c'n be easily compared t' th' image, proofread, an' sent back t' th' site. A second matey then be presented with th' work o' the' first an' th' same page image, verifies an' corrects th' work as necessary, an' submits it back t' th' site. Th' book then be makin' passage through a pair o' formattin' rounds usin' th' same web interface."); ?></p>

<p><? echo _("Once th' ship-shape pages be reachin' safe harbor, a post-processor crafts 'em in comely form t' be submittin' it t' th' Project Gutenberg arrrchive."); ?></p>

<p><font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("How Ye 'Lubbers Can Join th' Crew"); ?></b></font><br>
<ul>
<li>
<? echo sprintf(_("R'cord yer name in th' <a href='accounts/addproofer.php'>Muster-book</a> as a sprog, ye swabbie.")); ?></li>
<li>
<? echo sprintf(_("Make sure ye be readin' yer introductory email an' th' <a href='%s/faq/ProoferFAQ.php'>Beginnin' Proofreader's FAQ</a>."),$code_url); ?></li>
<li>
<? echo sprintf(_("Confirm yer registration, sign in, choose a project, an' sink yer hooks in a page or two!")); ?></li>
</ul>
<p>
<? echo sprintf(_("We be invitin' unregistered guests t' privateer in the Sea of <a href='tools/post_proofers/smooth_reading.php'>Smooth Readin'</a>.")); ?>
</p>

<p><? echo _("Ye'll mind we be expectin' nary a commitment on th' site past th' understandin' that ye do yer best.");?></p>
<p><? echo _("Ye c'n stand watch as oft'n or as seldom as ye like, an' proofread as many or as wee pages as ye like. We be encouragin' swabbies t' do a page every tide, but ye c'n keep yer own mind on't! We be hopin' ye'll join us in our voyage o' 'preservin' th' lit'rary history o' th' world in a freely available form fer sea dogs an' land lubbers alike'."); ?></p>
<p><? echo sprintf(_("If ye be havin' a fierce fire in yer belly t' aid an' abet this 'ere endeavour, there's room from th' bilge t' th' kites fer managin' projects, providin' content, or even 'elpin' develop improvements to the site! Gather 'round wi' yer mateys for <a href='%s'>the Scuttlebutt</a>, an' spin a yarn on these an' many other topics."),$forums_url); ?></p>

<font face="<? echo $theme['font_mainbody']; ?>" color="<? echo $theme['color_headerbar_bg']; ?>" size="+1"><b><? echo _("Prizes an' Plunder!"); ?></b></font><br>

<?
echo "<table><tr><td valign='top'>";

//Gold E-texts
showstartexts($etext_limit,'gold'); echo "</td><td valign='top'>";

//Silver E-texts
showstartexts($etext_limit,'silver'); echo "</td><td valign='top'>";

//Bronze E-texts
showstartexts($etext_limit,'bronze'); echo "</td></tr></table>";

echo "<hr><center>\n";
echo _("Thar be no need fer th' shackles: our crew o' proofreaders, project managers, developers, an' th' like is composed entirely o' volunteers.");
echo "</center>\n";

// Show the number of users that have been active over various recent timescales.
foreach ( array(1,7,30) as $days_back )
{
    $res = mysql_query("
        SELECT COUNT(*)
        FROM users
        WHERE t_last_activity > UNIX_TIMESTAMP() - $days_back * 24*60*60
    ") or die(mysql_error());
    $num_users = mysql_result($res,0);
    
    $template = (
        $days_back == 1
        ? _('%s mateys aboard in th\' past seven watches.')
        : _('%s mateys aboard in th\' past %d days.')
    );
    $msg = sprintf( $template, number_format($num_users), $days_back );
    echo "<center><i><b>$msg</b></i></center>\n";
}

echo "<hr><center>\n";
echo sprintf(_("If ye be 'avin' questions an' comments, send a carrier-parrot t' th' Captain's Mast at <a href='mailto:%s'>%s</a>."),$general_help_email_addr,$general_help_email_addr);
echo "</center>&nbsp;<br>\n";

theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>
