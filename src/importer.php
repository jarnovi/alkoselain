<?php
/** Importing functionality. That is, turning the fetched excel data into our format and storing it. */

require_once "model.php";
require_once 'SimpleXLSX.php';

function xlxs_to_drinks($file)
{
	$xlsx = SimpleXLSX::parseData($file);
	if (!$xlsx->success()) throw new Exception($xlsx->error());

	$rows =  $xlsx->rows();
	$products_size = sizeof($rows) - 4;
	$drinks = [];

	for ($i = 4; $i < $products_size + 4; $i++) {
		//echo $tuotelista[$i][10]."<br>" ;

		// $numero,$nimi,$valmistaja,$pullokoko,$hinta,$litrahinta,$tyyppi,$valmistusmaa,$vuosikerta,$alkoholiprosentti,$energia

		$current_row = $rows[$i];

		// TODO: Propper type conversions

		$drink = new Drink();
		$drink->number = $current_row[0];
		$drink->name = $current_row[1];
		$drink->manufacturer = $current_row[2];
		$drink->size_in_milliliters = $current_row[3];
		$drink->price = $current_row[4];
		$drink->price_per_liter = $current_row[5];
		$drink->type = $current_row[8];
		$drink->origin = $current_row[12];
		$drink->vintage = $current_row[14];
		$drink->percentage = $current_row[21];
		$drink->kcal_per_hundred_ml = $current_row[27];
	}

	return $drinks;
}

?>
