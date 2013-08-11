<?PHP
include_once($relPath.'http_headers.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc');
include_once('PPage.inc');

// $ppage is assumed to be set before this file is included.


// set image and text height and width
  if ($userP['i_layout']=='1')
      {
        $textWidth=$userP['v_tframe'];
        $imageWidth=(100-$userP['v_tframe'])-1;
        $textHeight=99;
        $imageHeight=99;
        $textTop="0px";
        $textLeft=(100-$userP['v_tframe'])."%";
      }
  else
      {
        $textWidth=99;
        $imageWidth=99;
        $textHeight=$userP['h_tframe'];
        $imageHeight=(100-$userP['h_tframe'])-1;
        $textTop=(100-$userP['h_tframe'])."%";
        $textLeft="1%";
      }

slim_header("Proofreading Page",TRUE,FALSE);
?>
<script language="JavaScript" type="text/javascript">
<!--
function ldAll()
{top.initializeStuff(1);}
function scrollImage(sDir)
{top.scrollImage(sDir);}
function scrollOver(sDir)
{top.scrollOver(sDir);}
function stopOver()
{top.stopOver();}
function getCurSel()
{top.getCurSel();}
function getCurCaret()
{top.getCurCaret();}
function showIZ()
{top.showIZ();
return false;}
function showActual()
{ return top.showActual(); }
// -->
</script>
<style type="text/css">
<!--
body {
  font-family: verdana, arial, helvetica, sans-serif;
  font-size: 12px;
  color:#000000;
  background-color:#CDCDC1;
  text-align:center;
  overflow:hidden;
  }
A:link {
  color:#000000;
  text-decoration : none;
  }
A:visited {
  color:#000000;
  text-decoration : none;
  }
A:hover {
  color:#003300;
  font-weight: bold;
  text-decoration : none;
  }
A:active {
  color:#000033;
  font-weight: bold;
  text-decoration : none;
  }
#imagehorz {
  position:absolute;
  left:25px;
  top:0px;
  <?PHP
    echo "width:".($imageWidth-3)."%;\r\n";
  ?>
  height:25px;
  z-index:3;
  }
#imagevert {
  position:absolute;
  left:0px;
  top:25px;
  width:25px;
  <?PHP
    echo "height:".($imageHeight-3)."%;\r\n";
  ?>
  z-index:4;
  }
#imageframe {
  <?PHP
/*    if ($userP['i_layout']=='1')
      {
        echo "position:absolute;\r\n".
          "top:0px;\r\n".
          "left:0px;\r\n";
        echo "width:".$imageWidth."%;\r\n";
      }
    else {echo "position:relative;\r\n";}
*/
    echo "position:absolute;\r\n".
      "top:25px;\r\n".
      "left:25px;\r\n";
    echo "width:".($imageWidth-3)."%;\r\n";
    echo "height:".($imageHeight-3)."%;\r\n";
  ?>
  clip:rect(0px, 100%, 100%, 0px);
  z-index:2;
  overflow:auto;
  text-align:center;
  }
#imagedisplay {
  position:absolute;
  left:0px;
  top:0px;
  z-index:1;
  background-color:#EEDFCC;
  }
#controlframe {
  <?PHP
/*    if ($userP['i_layout']=='1')
      {
        echo " position:absolute;\r\n";
        echo "left:".$textLeft.";\r\n";
        echo "top:".$textTop.";\r\n";
        echo "width:".$textWidth."%;\r\n";
      }
    else {echo "position:relative;\r\n";}
*/
    echo " position:absolute;\r\n";
    echo "left:".$textLeft.";\r\n";
    echo "top:".$textTop.";\r\n";
    echo "width:".$textWidth."%;\r\n";
    echo "height:".$textHeight."%;\r\n";
  ?>
  clip:rect(0px, 100%, 100%, 0px);
  background-color:#CE928C;
  overflow:auto;
  z-index:6;
  text-align:center;
  }
#tbtext {
  border:1px solid #000000;
  text-align:center;
  overflow:auto;
  }
#tdtop {
  border:1px solid #000000;
  background-color:#CDC0B0;
  padding:2px;
  }
#tdtext {
  border:1px solid #000000;
  background-color:#CE928C;
  padding:2px;
  }
#tdbottom {
  border:1px solid #000000;
  background-color:#EEDFCC;
  padding:2px;
  }
#text_data {
  padding:2px;
  background-color:#FFF8DC;
  color:#000000;
  }
.dropsmall {
  font-size: 75%;
  background-color:#FFF8DC;
  }
.dropnormal {
  background-color:#FFF8DC;
  }
.boxnormal {
  background-color:#FFF8DC;
  }
-->
</style>
</head><body
  text="#000000"
  topmargin="0"
  onload="ldAll()"><div
id="imagehorz"><table
  id="tbhorz"
  width="100%"><tr><td
    align="left"><a
    href="JavaScript:scrollImage('left')"><img
      src="gfx/a1_left.png" width="11" height="11" alt="Move Left" title="Move Left" border="0"></a>&nbsp;&nbsp;&nbsp;<a
    href="JavaScript: //" onmouseover="scrollOver('left')" onmouseout="stopOver()"><img
      src="gfx/a2_left.png" width="11" height="11" alt="Scroll Left" title="Scroll Left" border="0"></a></td><td
      align="right"><a
      href="JavaScript: //" onmouseover="scrollOver('right')" onmouseout="stopOver()"><img
        src="gfx/a2_right.png" width="11" height="11" alt="Scroll Right" title="Scroll Right" border="0"></a>&nbsp;&nbsp;&nbsp;<a
     href="JavaScript:scrollImage('right')"><img
       src="gfx/a1_right.png" width="11" height="11" alt="Move Right" title="Move Right" border="0"></a></td></tr></table></div><div
id="imagevert"><table
  id="tbvert"
  height="95%"><tr><td
    valign="top"><a
    href="JavaScript:scrollImage('up')"><img
      src="gfx/a1_up.png" width="11" height="11" alt="Move Up" title="Move Up" border="0"></a><p><a
    href="JavaScript: //" onmouseover="scrollOver('up')" onmouseout="stopOver()"><img
      src="gfx/a2_up.png" width="11" height="11" alt="Scroll Up" title="Scroll Up" border="0"></a></p></td></tr><tr><td
      valign="bottom"><a
      href="JavaScript: //" onmouseover="scrollOver('down')" onmouseout="stopOver()"><img
        src="gfx/a2_down.png" width="11" height="11" alt="Scroll Down" title="Scroll Down" border="0"></a><p><a
    href="JavaScript:scrollImage('down')"><img
      src="gfx/a1_down.png" width="11" height="11" alt="Move Down" title="Move Down" border="0"></a></p></td></tr></table></div><div
id="imageframe"><div
id="imagedisplay"><a href="JavaScript: //"><img
name="scanimage" id="scanimage" title="" alt=""
src="<?PHP echo $ppage->url_for_image();?>"
border="0" width="<?PHP
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
  $iWidth=round((1000*$iWidth)/100);
  echo $iWidth;
?>"></a></div></div><div
id="controlframe" align="center"><form
      name="editform" id="editform"
      method="POST"
      action="processtext.php"><?PHP

$ppage->echo_hidden_fields();

?><table
  id="tbtext"
  cellspacing="0"
  align="center"><tr><td
    id="tdtop"
    valign="middle"><?PHP
  include('button_menu.inc');
?></td></tr><tr><td
  id="tdtext"
  valign="top"><?PHP

  $ppage->echo_proofing_textarea( TRUE );

?></td></tr><tr><td
  id="tdbottom"
  valign="top"><?PHP

$ppage->echo_info();

?>
  </td>
  </tr>
  </table>
  </form>
  </div>
</body>
</html>