<?php
function huecos_fila($i,$h)
  {
  $posarr=0;
  $arr=array();
  $cont=0;
  foreach($_SESSION['col'] as $key => $value)
    {
    if($_SESSION['tablero'][$h][$key]==$i || ($_SESSION['tablero'][$h][$key]==0 && $value[$i]['c']>0))
      {
      $cont++;
      $arr[$posarr]['cont']=$cont;
      $arr[$posarr]['pos']=$key-$cont+1;
      }
    else
      {
      $cont=0;
      $posarr++;
      }
    }
  return $arr;
  }

function huecos_col($i,$w)
  {
  $posarr=0;
  $arr=array();
  $cont=0;
  foreach($_SESSION['fila'] as $key => $value)
    {
    if($_SESSION['tablero'][$key][$w]==$i || ($_SESSION['tablero'][$key][$w]==0 && $value[$i]['c']>0))
      {
      $cont++;
      $arr[$posarr]['cont']=$cont;
      $arr[$posarr]['pos']=$key-$cont+1;
      }
    else
      {
      $cont=0;
      $posarr++;
      }
    }
  return $arr;
  }

function por_bloques_fila()
  {
  foreach ($_SESSION['fila'] as $h=>$colores)
    {
    foreach ($colores as $i=>$value)
      {
      if($value['c']>0 && $_SESSION['filaorg'][$h][$i]['b']=='on')
        {
        $tam=$_SESSION['filaorg'][$h][$i]['c'];
        $huecos=huecos_fila($i,$h);
        //echo('fila:'.$h.'=>color:'.$i.'=>tam:'.$tam.'<pre>');print_r($huecos);echo('</pre>');
        $cont=0;
        $ind=0;
        foreach($huecos as $key => $value)
          {
          if ($value['cont']>=$tam) {$cont++;$ind=$key;}
          }
        if ($cont==1)
          {
          if ($huecos[$ind]['cont']<=(($tam*2)-1))
            {
            $offset=$huecos[$ind]['cont']-$tam;
            $cant=$tam-$offset;
            $posini=$huecos[$ind]['pos']+$offset;
            $posfin=$posini+$cant-1;
            for ($j=$posini;$j<=$posfin;$j++)
              {
              punto_condicional($h,$j,$i);
              }
            }
          }
        }
      }
    }
  }

function por_bloques_col()
  {
  foreach ($_SESSION['col'] as $w=>$colores)
    {
    foreach ($colores as $i=>$value)
      {
      if($value['c']>0 && $_SESSION['colorg'][$w][$i]['b']=='on')
        {
        $tam=$_SESSION['colorg'][$w][$i]['c'];
        $huecos=huecos_col($i,$w);
        //echo('col:'.$w.'=>color:'.$i.'=>tam:'.$tam.'<pre>');print_r($huecos);echo('</pre>');
        $cont=0;
        $ind=0;
        foreach($huecos as $key => $value)
          {
          if ($value['cont']>=$tam) {$cont++;$ind=$key;}
          }
        if ($cont==1)
          {
          if ($huecos[$ind]['cont']<=(($tam*2)-1))
            {
            $offset=$huecos[$ind]['cont']-$tam;
            $cant=$tam-$offset;
            $posini=$huecos[$ind]['pos']+$offset;
            $posfin=$posini+$cant-1;
            for ($j=$posini;$j<=$posfin;$j++)
              {
              punto_condicional($j,$w,$i);
              }
            }
          }
        }
      }
    }
  }

function por_bloques()
  {
  por_bloques_fila();
  por_bloques_col();
  }

function cuantos_caben($t,$i,$p)
  {
  $n=0;
  $arr=array();
  if($t=='f') $arr=$_SESSION['fila']; else $arr=$_SESSION['col'];
  foreach($arr as $key=>$value)
    {
    if($t=='f'){$h=$key;$w=$p;} else {$h=$p;$w=$key;}
    if($value[$i]['c']>0 && $_SESSION['tablero'][$h][$w]==0) $n++;
    }
  return $n;
  }

function punto_seguro($f,$c,$i)
  {
  $_SESSION['tablero'][$f][$c]=$i;
  $_SESSION['fila'][$f][$i]['c']--;
  $_SESSION['col'][$c][$i]['c']--;
  }

function punto_condicional($f,$c,$i)
  {
  if($_SESSION['tablero'][$f][$c]==0)
    {
    $_SESSION['tablero'][$f][$c]=$i;
    $_SESSION['fila'][$f][$i]['c']--;
    $_SESSION['col'][$c][$i]['c']--;
    }
  }

function pinta_color($t,$n,$i)
  {
  $arr=array();
  if($t=='f') $arr=$_SESSION['col']; else $arr=$_SESSION['fila'];
  foreach($arr as $key=>$value)
    {
    if($t=='f'){$h=$n;$w=$key;} else {$h=$key;$w=$n;}
    if($value[$i]['c']>0 && $_SESSION['tablero'][$h][$w]==0)
      {
      punto_seguro($h,$w,$i);
      }
    }
  }

function por_cantidad()
  {
  //echo('por_cantidad:');
  for ($h=0;$h<$_SESSION['h'];$h++)
    {//echo('<br/>h:'.$h);
    for ($i=1;$i<=4;$i++)
      {//echo('<br/>i:'.$i.'->');
      if ($_SESSION['fila'][$h][$i]['c']>0)
        {//echo('c:'.$_SESSION['fila'][$h][$i]['c'].'->'.cuantos_caben('c',$i).'<br/>');
        if($_SESSION['fila'][$h][$i]['c']==cuantos_caben('c',$i,$h)) pinta_color('f',$h,$i);
        }
      }
    }
  //echo('por_cantidad:');
  for ($w=0;$w<$_SESSION['w'];$w++)
    {//echo('<br/>h:'.$h);
    for ($i=1;$i<=4;$i++)
      {//echo('<br/>i:'.$i.'->');
      if ($_SESSION['col'][$w][$i]['c']>0)
        {//echo('c:'.$_SESSION['fila'][$h][$i]['c'].'->'.cuantos_caben('c',$i).'<br/>');
        if($_SESSION['col'][$w][$i]['c']==cuantos_caben('f',$i,$w)) pinta_color('c',$w,$i);
        }
      }
    }
  }

function load()
  {
  $_SESSION=unserialize(file_get_contents('savestate.txt'));
  /*
  $_SESSION['c1']=4;
  $_SESSION['c2']=5;
  $_SESSION['c3']=7;
  $_SESSION['c4']=6;
  $_SESSION['w']=10;
  $_SESSION['h']=15;
  $_SESSION['tablero']=array();
  for ($h=0;$h<$_SESSION['h'];$h++)
    {
    for ($w=0;$w<$_SESSION['w'];$w++)
      {
      $_SESSION['tablero'][$h][$w]=0;
      }
    }
  $_SESSION['fila']=unserialize('a:15:{i:0;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:1:{s:1:"c";s:1:"4";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:2:{s:1:"c";s:1:"6";s:1:"b";s:2:"on";}}i:1;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:1:{s:1:"c";s:1:"6";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:2:{s:1:"c";s:1:"4";s:1:"b";s:2:"on";}}i:2;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:1:{s:1:"c";s:1:"6";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"4";}}i:3;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:2:{s:1:"c";s:1:"6";s:1:"b";s:2:"on";}i:3;a:1:{s:1:"c";s:1:"2";}i:4;a:1:{s:1:"c";s:1:"2";}}i:4;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:3;a:1:{s:1:"c";s:1:"6";}i:4;a:1:{s:1:"c";s:1:"2";}}i:5;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:1:{s:1:"c";s:1:"2";}i:3;a:2:{s:1:"c";s:1:"6";s:1:"b";s:2:"on";}i:4;a:1:{s:1:"c";s:1:"2";}}i:6;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:1:{s:1:"c";s:1:"1";}i:3;a:1:{s:1:"c";s:1:"5";}i:4;a:1:{s:1:"c";s:1:"4";}}i:7;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:1:{s:1:"c";s:1:"1";}i:3;a:1:{s:1:"c";s:1:"3";}i:4;a:1:{s:1:"c";s:1:"6";}}i:8;a:4:{i:1;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"8";}}i:9;a:4:{i:1;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"8";}}i:10;a:4:{i:1;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"8";}}i:11;a:4:{i:1;a:1:{s:1:"c";s:1:"5";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"5";}}i:12;a:4:{i:1;a:1:{s:1:"c";s:1:"6";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"4";}}i:13;a:4:{i:1;a:1:{s:1:"c";s:1:"6";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"4";}}i:14;a:4:{i:1;a:2:{s:1:"c";s:1:"6";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"0";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:1:"4";}}}');
  $_SESSION['filaorg']=$_SESSION['fila'];
  $_SESSION['col']=unserialize('a:10:{i:0;a:4:{i:1;a:1:{s:1:"c";s:1:"0";}i:2;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:2:{s:1:"c";s:2:"13";s:1:"b";s:2:"on";}}i:1;a:4:{i:1;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"4";}i:3;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:4;a:1:{s:1:"c";s:1:"6";}}i:2;a:4:{i:1;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:2;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:3;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:4;a:1:{s:1:"c";s:1:"6";}}i:3;a:4:{i:1;a:1:{s:1:"c";s:1:"1";}i:2;a:1:{s:1:"c";s:1:"3";}i:3;a:1:{s:1:"c";s:1:"3";}i:4;a:1:{s:1:"c";s:1:"8";}}i:4;a:4:{i:1;a:2:{s:1:"c";s:1:"7";s:1:"b";s:2:"on";}i:2;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:3;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:4;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}}i:5;a:4:{i:1;a:2:{s:1:"c";s:1:"7";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"3";}i:3;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:4;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}}i:6;a:4:{i:1;a:1:{s:1:"c";s:1:"1";}i:2;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:3;a:2:{s:1:"c";s:1:"4";s:1:"b";s:2:"on";}i:4;a:1:{s:1:"c";s:1:"8";}}i:7;a:4:{i:1;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:2;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:3;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:4;a:1:{s:1:"c";s:1:"6";}}i:8;a:4:{i:1;a:2:{s:1:"c";s:1:"3";s:1:"b";s:2:"on";}i:2;a:1:{s:1:"c";s:1:"4";}i:3;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:4;a:1:{s:1:"c";s:1:"6";}}i:9;a:4:{i:1;a:1:{s:1:"c";s:1:"1";}i:2;a:2:{s:1:"c";s:1:"2";s:1:"b";s:2:"on";}i:3;a:1:{s:1:"c";s:1:"0";}i:4;a:1:{s:1:"c";s:2:"12";}}}');
  $_SESSION['colorg']=$_SESSION['col'];
  */
  }

function comunes($h,$w)
  {
  $arr=array();
  $posarr=0;
  foreach($_SESSION['fila'][$h] as $i=>$valfila)
    {
    foreach($_SESSION['col'][$w] as $j=>$valcol)
      {
      if($i==$j && $valfila['c']>0 && $valcol['c']>0)
        {
        $vecinos_fila=cuenta_vecinos_fila($h,$w,$i);
        $vecinos_columna=cuenta_vecinos_col($h,$w,$j);
        //echo('h:'.$h.'=>w:'.$w.'=>i:'.$i.'<pre>');print_r($vecinos_fila);print_r($vecinos_columna);echo('</pre>');
        if (($_SESSION['filaorg'][$h][$i]['b']=='on' || $_SESSION['filaorg'][$h][$i]['c']==1 ||(!isset($_SESSION['filaorg'][$h][$i]['b']) && $vecinos_fila+1!=$_SESSION['filaorg'][$h][$i]['c'])) && ($_SESSION['colorg'][$w][$i]['b']=='on' || $_SESSION['colorg'][$w][$i]['c']==1 || (!isset($_SESSION['colorg'][$w][$i]['b']) && $vecinos_columna+1!=$_SESSION['colorg'][$w][$i]['c'])))
          {
          $hbfila=huecos_bloque_fila($h,$i);
          $cantfila=$hbfila['fin']-$hbfila['ini']+1;
          $cantorgfila=$_SESSION['filaorg'][$h][$i]['c'];
          $distfila=$hbfila['ini']>$w?$hbfila['ini']-$w:$w-$hbfila['fin'];
          $totalfila=$cantfila+$distfila;
          $hbcol=huecos_bloque_col($w,$i);
          $cantcol=$hbcol['fin']-$hbcol['ini']+1;
          $cantorgcol=$_SESSION['colorg'][$w][$j]['c'];
          $distcol=$hbcol['ini']>$h?$hbcol['ini']-$h:$h-$hbcol['fin'];
          $totalcol=$cantcol+$distcol;
          //echo('h:'.$h.'=>w:'.$w.'=>i:'.$i.'=>cantf:'.$cantfila.'=>orgfila:'.$cantorgfila.'=>distfila:'.$distfila.'<pre>');print_r($hbfila);print_r($hbcol);echo('</pre>');
          if((!isset($_SESSION['filaorg'][$h][$i]['b']) || $hbfila['ini']==-1 || ($_SESSION['filaorg'][$h][$i]['b']=='on' && $totalfila<=$cantorgfila)) && (!isset($_SESSION['colorg'][$w][$j]['b']) || $hbcol['ini']==-1 ||($_SESSION['colorg'][$w][$j]['b']=='on' && $totalcol<=$cantorgcol)))
            {//echo('ok<br/>');
            $arr[$posarr++]=$i;
            }
          }
        }
      }
    }
  return $arr;
  }

function por_comunes()
  {
  foreach($_SESSION['tablero'] as $h=>$columnas)
    {
    foreach($columnas as $w=>$v)
      {
      $arr=comunes($h,$w);
      if($v==0 && sizeof($arr)==1) punto_seguro($h,$w,$arr[0]);
      }
    }
  }

function huecos_bloque_fila($h,$i)
  {
  $arr=array('ini'=>-1,'fin'=>-1);
  foreach($_SESSION['tablero'][$h] as $w=>$v)
    {
    if ($v==$i)
      {
      if ($arr['ini']==-1) $arr['ini']=$w;
      $arr['fin']=$w;
      }
    }
  return $arr;
  }

function huecos_bloque_col($w,$i)
  {
  $arr=array('ini'=>-1,'fin'=>-1);
  for($h=0;$h<$_SESSION['h'];$h++)
    {
    if ($_SESSION['tablero'][$h][$w]==$i)
      {
      if ($arr['ini']==-1) $arr['ini']=$h;
      $arr['fin']=$h;
      }
    }
  return $arr;
  }

function por_huecos_bloque_fila()
  {
  foreach ($_SESSION['fila'] as $h=>$colores)
    {
    foreach ($colores as $i=>$value)
      {
      if($value['c']>0 && $_SESSION['filaorg'][$h][$i]['b']=='on')
        {
        $arr=huecos_bloque_fila($h,$i);
        //echo('h:'.$h.'=>i:'.$i.'<pre>');print_r($arr);echo('</pre>');
        if ($arr['ini']>-1 && $arr['fin']>$arr['ini'])
          {
          for ($j=$arr['ini'];$j<=$arr['fin'];$j++) punto_condicional($h,$j,$i);
          }
        if ($arr['ini']>-1 && $_SESSION['tablero'][$h][$arr['ini']-1]!==0)
          {
          for ($j=$arr['ini'];$j<$arr['ini']+$_SESSION['filaorg'][$h][$i]['c'];$j++) punto_condicional($h,$j,$i);
          }
        $arr=huecos_bloque_fila($h,$i);
        if ($arr['fin']>-1 && $_SESSION['tablero'][$h][$arr['fin']+1]!==0)
          {
          for ($j=$arr['fin'];$j>$arr['fin']-$_SESSION['filaorg'][$h][$i]['c'];$j--) punto_condicional($h,$j,$i);
          }
        }
      }
    } 
  }

function por_huecos_bloque_col()
  {
  foreach ($_SESSION['col'] as $w=>$colores)
    {
    foreach ($colores as $i=>$value)
      {
      if($value['c']>0 && $_SESSION['colorg'][$w][$i]['b']=='on')
        {
        $arr=huecos_bloque_col($w,$i);
        if ($arr['ini']>-1 && $arr['fin']>$arr['ini'])
          {
          for ($j=$arr['ini'];$j<=$arr['fin'];$j++) punto_condicional($j,$w,$i);
          }
        if ($arr['ini']>-1 && $_SESSION['tablero'][$arr['ini']-1][$w]!==0)
          {
          for ($j=$arr['ini'];$j<$arr['ini']+$_SESSION['colorg'][$w][$i]['c'];$j++) punto_condicional($j,$w,$i);
          }
        $arr=huecos_bloque_col($w,$i);
        if ($arr['fin']>-1 && $_SESSION['tablero'][$arr['fin']+1][$w]!==0)
          {
          for ($j=$arr['fin'];$j>$arr['fin']-$_SESSION['colorg'][$w][$i]['c'];$j--) punto_condicional($j,$w,$i);
          }
        }
      }
    } 
  }

function por_huecos_bloque()
  {
  por_huecos_bloque_fila();
  por_huecos_bloque_col();
  }

function cuantos_faltan()
  {
  $cont=0;
  foreach($_SESSION['tablero'] as $h=>$columnas)
    {
    foreach($columnas as $w=>$v)
      {
      if($v==0) $cont++;
      }
    }
  return $cont;
  }

function cuenta_vecinos_fila($h,$w,$i)
  {
  $cont=0;
  $sigue=1;
  $pos=$w;
  while($sigue==1)
    {
    $pos++;
    if($_SESSION['tablero'][$h][$pos]===$i)
      {
      $cont++;
      }
    else
      {
      $sigue=0;
      }
    }
  $sigue=1;
  $pos=$w;
  while($sigue==1)
    {
    $pos--;
    if($_SESSION['tablero'][$h][$pos]===$i)
      {
      $cont++;
      }
    else
      {
      $sigue=0;
      }
    }
  return $cont;
  }

function cuenta_vecinos_col($h,$w,$i)
  {
  $cont=0;
  $sigue=1;
  $pos=$h;
  while($sigue==1)
    {
    $pos++;
    if($_SESSION['tablero'][$pos][$w]===$i)
      {
      $cont++;
      }
    else
      {
      $sigue=0;
      }
    }
  $sigue=1;
  $pos=$h;
  while($sigue==1)
    {
    $pos--;
    if($_SESSION['tablero'][$pos][$w]===$i)
      {
      $cont++;
      }
    else
      {
      $sigue=0;
      }
    }
  return $cont;
  }

function todos()
  {
  por_cantidad();
  por_bloques();
  por_comunes();
  por_huecos_bloque();
  }

function save()
  {
  file_put_contents('savestate.txt',serialize($_SESSION));
  }
?>
