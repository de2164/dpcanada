<?PHP
$relPath='../../pinc/';
include($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
$project = $_GET['project'];
$imagefile = $_GET['imagefile'];

if (!empty($_GET['percent'])) {
    $width = 10 * $_GET['percent'];
} else $width = 1000;

echo "<html><head></head><body>";
?>

<form method="get" action="displayimage.php">

<input type="hidden" name="project" value="<?echo $project;?>">
<input type="hidden" name="imagefile" value="<?echo $imagefile;?>">
Resize:
<input type="text" maxlength="3" name="percent" size="3" value="<?echo
$_GET['percent'];?>"> % <input type="submit" value="Resize" size="3">
Jump to:
<select name="jumpto" onChange="this.form.imagefile.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();">
<?
new dbConnect();
$res = mysql_query( "SELECT image FROM $project ORDER BY image ASC") or die(mysql_error());
$num_rows = mysql_num_rows($res);
$prev_image = "";
$next_image = "";
for ($row=0; $row<$num_rows;$row++)
{
    $this_val = mysql_result($res, $row, "image");
    echo "<option value=\"$this_val\"";
    if ($this_val == $imagefile)
        {
        echo " selected";
	if ( $row != 0 ) $prev_image = mysql_result($res, $row-1, "image");
	if ( $row != $num_rows-1 ) $next_image = mysql_result($res, $row+1, "image");
        }
    echo ">".$this_val."</option>\n";
}
?>
</select>
<?
echo "<input type=\"button\" value=\"Previous\" onClick=\"this.form.imagefile.value='$prev_image'; this.form.submit();\"";
if ( $prev_image == "" ) echo " disabled";
echo ">\n";
echo "<input type=\"button\" value=\"Next\" onClick=\"this.form.imagefile.value='$next_image'; this.form.submit();\"";
if ( $next_image == "" ) echo " disabled";
echo ">\n";
?>

</form>

<?
$myresult = mysql_query("SELECT nameofwork, state FROM projects WHERE projectid = '$project'");
$row = mysql_fetch_assoc($myresult);
$state = $row['state'];
$title = $row['nameofwork'];

$label = _("Return to Project Page for");

echo "<a href='$code_url/project.php?id=$project&amp;expected_state=$state'>$label $title</a>";
echo "<br>\n";

printf ("<img src=\"$projects_url/%s/%s\" width=\"$width\" border=\"1\">", $project, $imagefile);
?>
</body></html>
