<doctype HTML>
    <html>
    <head><title>rahalla saa ja alkolla pääsee</title>
    <link href="./style.css" rel="stylesheet" />
    </head>
<body>
<?php 
 include_once 'alkofunktiot.php';

#if ($_GET["updatedb"]){excel2db();}
#excel2db('orig_alkon-hinnasto-tekstitiedostona.xlsx');

$sarakkeet=["numero","nimi","valmistaja","pullokoko","hinta","litrahinta","tyyppi","valmistusmaa","vuosikerta","alkoholiprosentti","energia"];

$sort   = isset($_GET['jarjesta'])  ? intval($_GET['jarjesta'])>0? intval($_GET['jarjesta']) : 2: 2 ;  
$dir    = isset($_GET['suunta'])    ? $_GET['suunta']=='laskeva' ? 'DESC' : 'ASC':'ASC' ;
$offset = isset($_GET['alku'])      ? intval($_GET['alku'])>0 ? intval($_GET['alku']) : 0:0 ;
$maara  = isset($_GET['maara'])     ? intval($_GET['maara'])>0 ? intval($_GET['maara']) : 25:25 ;
$filter = isset($_GET['filter'])    ? $_GET['filter'] : NAN;


print("<style>.title th:nth-child($sort){background-color:#1f1f1f !important;}
    th:nth-child($sort) a{color:white !important}</style>");

$prevoffs=$offset-$maara;
$nextoffs=$offset+$maara;



$postStringn="jarjesta=$sort&suunta=$dir&alku=$nextoffs&maara=$maara&filter=$filter";
$postStringp="jarjesta=$sort&suunta=$dir&alku=$prevoffs&maara=$maara&filter=$filter";

#print('<h1>Alkon tuotteet:</h1>');
#print("<h2>$sort => $sarakkeet[$sort]</h2>");
#print_r($_GET);
foreach ($_GET as $stuff){
#    print_r($stuff);
}
top20($sort,$dir,$maara,$offset);

print('<button onclick="window.location.href=\'index.php?'.$postStringp.'\';">aikaisempi</button>');
print('<button onclick="window.location.href=\'index.php?'.$postStringn.'\';">seuraava</button>');



?>
</body>
</html>