<?php 
require_once "model.php";
$db = require "db.php";
$table_creator = require "table.php";

$COLUMNS=["number","name","manufacturer","size","price","price_per_liter","type","origin","vintage","percentage","energy"];



$sort = isset($_GET['jarjesta']) ? (int)$_GET['jarjesta'] >0? (int)$_GET['jarjesta'] : 2: 2 ;
$direction = isset($_GET['suunta']) ? $_GET['suunta']=='laskeva' ? 'DESC' : 'ASC':'ASC' ;
$offset = isset($_GET['alku']) ? (int)$_GET['alku'] >0 ? (int)$_GET['alku'] : 0:0 ;
$amount = isset($_GET['maara']) ? (int)$_GET['maara'] >0 ? (int)$_GET['maara'] : 25:25 ;


//  lasketaan nappien muutokset     
$previous_offset=$offset-$amount;
$next_offser=$offset+$amount;

$db->migrate_db();
$latest_import_batch = $db->get_latest_import_batch();

$drinks = [];

if ($latest_import_batch) {
	$drinks = $db->fetch_drinks($latest_import_batch->id, $sort, $direction, $amount, $offset);
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
</head>
    <?php
        echo "<style>" . file_get_contents("./style.css") . "</style>";
    ?>
<body>
	<header>
        <div class="header-container">
            <h1>Alkon tuotekatalogi</h1>
            <!-- Maybe clean this up, or start using templates? -->
            <?php
            if ($latest_import_batch) {
                echo "<!--" . $latest_import_batch->id . "-->";
                echo "<div class='info-container'>
                        <p>:Päivitetty viimeksi: </p><br>
                           <time datetime='".$latest_import_batch->date->format("Y-m-d")."'>".
                    $latest_import_batch->date->format("d.m.Y")."
                           </time>
                      </div>";
            }   else {
                echo " <div class='warning-container'>
                       <p>Varoitus! Tietoja ei löytynyt.</p>
                       <p>Hae <br> <a href='./refresh.php' style='color:red;'>uudet tiedot</a></p>
                       </div>";
            }
            ?>
        </div>
	</header>

	<main>
        <div class='main-container'>
            <div class='table-container'>
                <?php echo $table_html; ?>
            </div>
            <div class='buttons-container'>
                <a class="button" href="?<?= $prev_page_query ?>">aikaisempi</a>
                <a class="button" href="?<?= $next_page_query ?>">seuraava</a>
            </div>
        </div>
	</main>
</body>
</html>
