<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
#completo input[type="text"], #filas input[type="text"] {
  width:18px;
  height:18px;
  border:0px;
  padding:0px;
  }

input[type="checkbox"]{
  margin:0px;
  vertical-align:middle;
  }

</style>
</head>
<body style="background-color:#c0c0c0;">
<?php
ini_set('display_errors',1);
session_start();
include('functions.php');
if($_GET['act']=='cant') por_cantidad();
if($_GET['act']=='bloq') por_bloques();
if($_GET['act']=='comu') por_comunes();
if($_GET['act']=='huec') por_huecos_bloque();
if($_GET['act']=='todo') todos();
if($_GET['act']=='save') save();
if($_GET['act']=='load') load();
if(isset($_POST['w'])) $_SESSION['w']=$_POST['w'];
if(isset($_POST['h'])) $_SESSION['h']=$_POST['h'];
if($_POST['changesize']=='true' || !isset($_SESSION['tablero']))
  {
  $_SESSION['tablero']=array();
  $_SESSION['fila']=array();
  $_SESSION['filaorg']=array();
  $_SESSION['col']=array();
  $_SESSION['colorg']=array();
  for ($h=0;$h<$_SESSION['h'];$h++)
    {
    for ($w=0;$w<$_SESSION['w'];$w++)
      {
      $_SESSION['tablero'][$h][$w]=0;
      for ($i=1;$i<=4;$i++) {$_SESSION['fila'][$h][$i]['c']=0;$_SESSION['col'][$w][$i]['c']=0;}
      }
    }
  }
if(isset($_POST['selc1'])) $_SESSION['c1']=$_POST['selc1'];
if(isset($_POST['selc2'])) $_SESSION['c2']=$_POST['selc2'];
if(isset($_POST['selc3'])) $_SESSION['c3']=$_POST['selc3'];
if(isset($_POST['selc4'])) $_SESSION['c4']=$_POST['selc4'];
if(is_array($_POST['fila'])) {$_SESSION['fila']=$_POST['fila'];$_SESSION['filaorg']=$_SESSION['fila'];}
if(is_array($_POST['col'])) {$_SESSION['col']=$_POST['col'];$_SESSION['colorg']=$_SESSION['col'];}
?>

<div id="paleta" style="display:inline-block;">
  <h2>paleta</h2>
  <form id="formpaleta" name="formpaleta" action="index.php" method="post">
    col1:<?php if (isset($_SESSION['c1'])) echo('<img src="plain.php?z=2&c='.$_SESSION['c1'].'"/>'); else echo("x"); ?><select id="selc1" name="selc1"><?php for ($i=0;$i<=7;$i++) {echo('<option value="'.$i.'"');$selected=$_SESSION['c1']==$i?' selected="selected"':'';echo($selected.' style="background-image:url(plain.php?z=1&c='.$i.');">'.$i.'</option>');}?></select>
    col2:<?php if (isset($_SESSION['c2'])) echo('<img src="plain.php?z=2&c='.$_SESSION['c2'].'"/>'); else echo("x"); ?><select id="selc2" name="selc2"><?php for ($i=0;$i<=7;$i++) {echo('<option value="'.$i.'"');$selected=$_SESSION['c2']==$i?' selected="selected"':'';echo($selected.' style="background-image:url(plain.php?z=1&c='.$i.');">'.$i.'</option>');}?></select>
    col3:<?php if (isset($_SESSION['c3'])) echo('<img src="plain.php?z=2&c='.$_SESSION['c3'].'"/>'); else echo("x"); ?><select id="selc3" name="selc3"><?php for ($i=0;$i<=7;$i++) {echo('<option value="'.$i.'"');$selected=$_SESSION['c3']==$i?' selected="selected"':'';echo($selected.' style="background-image:url(plain.php?z=1&c='.$i.');">'.$i.'</option>');}?></select>
    col4:<?php if (isset($_SESSION['c4'])) echo('<img src="plain.php?z=2&c='.$_SESSION['c4'].'"/>'); else echo("x"); ?><select id="selc4" name="selc4"><?php for ($i=0;$i<=7;$i++) {echo('<option value="'.$i.'"');$selected=$_SESSION['c4']==$i?' selected="selected"':'';echo($selected.' style="background-image:url(plain.php?z=1&c='.$i.');">'.$i.'</option>');}?></select>
    <input type="submit"/>
  </form>
</div>

<div id="tamaño" style="display:inline-block;">
  <h2>tamaño</h2>
  <form id="formtablero" name="formtablero" action="index.php" method="post">
    w:<input type="text" size="2" id="w" name="w" value="<?php if (isset($_SESSION['w'])) echo $_SESSION['w'];?>" />
    h:<input type="text" size="2" id="h" name="h" value="<?php if (isset($_SESSION['h'])) echo $_SESSION['h'];?>" />
    <input type="hidden" name="changesize" value="true"/>
    <input type="submit"/>
  </form>
</div>

<div id="completo">
<form id="formcompleto" action="index.php" method="post">
  <table border="1" style="border-collapse:collapse;">
    <tr>
    <?php
    echo('<td>faltan:'.cuantos_faltan().'</td>');
    foreach($_SESSION['col'] as $w=>$colors)
      {
      echo('<td>');
      foreach ($colors as $i=>$value)
        {
        echo('<input type="text" name="col['.$w.']['.$i.'][c]" value="'.$value['c'].'" style="background-image:url(plain.php?z=1&c='.$_SESSION['c'.$i].')" /><br/><input type="checkbox" name="col['.$w.']['.$i.'][b]"');$checked=$value['b']=='on'?' checked="checked"':'';echo($checked.'/><br/>');
        }
      echo('</td>');
      }
    ?>
    </tr>
    <?php
    foreach($_SESSION['fila'] as $h=>$colors)
      {
      echo('<tr>');
      echo('<td>');
      foreach($colors as $i=>$value)
        {
        echo('<input type="text" name="fila['.$h.']['.$i.'][c]" value="'.$value['c'].'" style="background-image:url(plain.php?z=1&c='.$_SESSION['c'.$i].')" /><input type="checkbox" name="fila['.$h.']['.$i.'][b]"');$checked=$value['b']=='on'?' checked="checked"':'';echo($checked.'/>');
        }
      echo('</td>');
      foreach($_SESSION['tablero'][$h] as $w=>$v)
        {
        echo('<td><img src="plain.php?x=19&y=19&z=1&c='.$_SESSION['c'.$v].'"/></td>');
        }
      echo('</tr>');
      }
    ?>
  </table>
<input type="submit"/>
</form>
<a href="index.php?act=cant">por cantidad</a>&nbsp;
<a href="index.php?act=bloq">por bloques</a>&nbsp;
<a href="index.php?act=comu">por comunes</a>&nbsp;
<a href="index.php?act=huec">por huecos</a>&nbsp;
<a href="index.php?act=todo">todos</a><br/>
<a href="index.php?act=save">save</a>&nbsp;
<a href="index.php?act=load">load</a><br/>
<?php
  if ($_GET['debug']==1) {echo("<pre>");print_r($_REQUEST);echo("</pre>");}
  if ($_GET['debug']==1) echo('fila:'.serialize($_SESSION['filaorg']).'<br/>');
  if ($_GET['debug']==1) echo('col:'.serialize($_SESSION['colorg']).'<br/>');
  if ($_GET['debug']==1) {echo("<pre>");print_r($_SESSION);echo("</pre>");}
?>
</div>
</body>
</html>
