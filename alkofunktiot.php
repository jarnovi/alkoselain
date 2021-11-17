<?php
 
include_once 'SimpleXLSX.php';
include_once 'SQLstuff.php';

function excel2db($file){
  initSQLdb();

  $rowcount=0;
  if ( $xlsx = SimpleXLSX::parse($file) ) {
        $tuotelista =  $xlsx->rows();
  } else {
    echo SimpleXLSX::parseError();
  }
  $tuottemaara = sizeof($tuotelista)-4;
  
  for ($i=4;$i<$tuottemaara+4;$i++){
      //echo $tuotelista[$i][10]."<br>" ;
      SQLAddProduct($tuotelista[$i][0],
      addslashes($tuotelista[$i][1]),
      addslashes($tuotelista[$i][2]),
      $tuotelista[$i][3], $tuotelista[$i][4],$tuotelista[$i][5],
      $tuotelista[$i][8],$tuotelista[$i][12],$tuotelista[$i][14],$tuotelista[$i][21],$tuotelista[$i][27]);
  }
}


function top20($sort,$dir,$maara,$alku){
    
  $vast=SQLtop20($sort,$dir,$maara,$alku);
   print("<table><tr class='title'>
    <th class='small'><a href='index.php?jarjesta=1'>numero</a></th>
    <th><a href='index.php?jarjesta=2'>nimi</a></th>
    <th><a href='index.php?jarjesta=3'>valmistaja</a></th>
    <th class='small'><a href='index.php?jarjesta=4'>Pullokoko</a></th>
    <th class='small'><a href='index.php?jarjesta=5'>hinta</a></th>
    <th class='small'><a href='index.php?jarjesta=6'>litrahinta</a></th>
    <th><a href='index.php?jarjesta=7'>tyyppi</a></th>
    <th><a href='index.php?jarjesta=8'>valmistusmaa</a></th>
    <th class='small'><a href='index.php?jarjesta=9'>vuosikerta</a></th>
    <th class='small'><a href='index.php?jarjesta=10'>alkoholi-%</a></th>
    <th class='small'><a href='index.php?jarjesta=11'>energia</a></th>
  </tr>
   ");
   
  
 foreach($vast as $ent){
    printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>
    ',
    $ent[0],$ent[1],$ent[2],$ent[3],$ent[4],$ent[5],$ent[6],$ent[7],$ent[8],$ent[9],$ent[10]);
  }
    print('<table>');
}
    



?>