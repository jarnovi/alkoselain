<?php 
require_once "model.php";
$db = require_once "db.php";
$table_creator = require_once "table.php";

$COLUMNS=["number","name","manufacturer","size","price","price_per_liter","type","origin","vintage","percentage","energy"];



$sort = isset($_GET['jarjesta']) ? intval($_GET['jarjesta'])>0? intval($_GET['jarjesta']) : 2: 2 ;  
$direction = isset($_GET['suunta']) ? $_GET['suunta']=='laskeva' ? 'DESC' : 'ASC':'ASC' ;
$offset = isset($_GET['alku']) ? intval($_GET['alku'])>0 ? intval($_GET['alku']) : 0:0 ;
$amount = isset($_GET['maara']) ? intval($_GET['maara'])>0 ? intval($_GET['maara']) : 25:25 ;


//  lasketaan nappien muutokset     
$previous_offset=$offset-$amount;
$next_offser=$offset+$amount;

$db->migrate_db();
$latest_import_batch = $db->get_latest_import_batch();

$drinks = [];

if ($latest_import_batch) {
	$db->fetch_drinks($latest_import_batch->$id, $sort, $direction, $amount, $offset);
}
$table_html = $table_creator->create($COLUMNS, $drinks);

$next_page_query="jarjesta=$sort&suunta=$direction&alku=$next_offser&maara=$amount";
$prev_page_query="jarjesta=$sort&suunta=$direction&alku=$previous_offset&maara=$amount";
?>
<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AlkoSelain/</title>
	<link href="./style.css" rel="stylesheet" />
</head>
<body>
	<header>
		<h1>Alkon tuotekatalogi</h1>
		<?php 
			if ($latest_import_batch) {
				echo "<!--" . $latest_import_batch->$id . "-->";
				echo "<p>Päivitetty viimeksi: <time datetime='" 
					. date("Y-m-d", $latest_import_batch->$date) . "/>"
					. date("d.m.Y", $latest_import_batch->$date) . "</time></p>";
			}  else {
				echo "<p>Varoitus! Tietoja ei löytynyt.</p>";
				echo "<p>Jos olet admini, hae <a href='/refresh.php'>uudet tiedot</a></p>";
			}
		?>
	</header>
	<main>
		<?php echo $table_html; ?>
		<a href="?<?= $prev_page_query ?>">aikaisempi</a>
		<a href="?<?= $next_page_query ?>">seuraava</a>
	</main>
</body>
</html>
