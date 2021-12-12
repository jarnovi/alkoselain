<?php

/** This module is responsible from fetching the excel sheet from Alko. */

function fetch_xlxs(): string
{
	$url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
	$res = file_get_contents($url);
	if ($res == false) throw new Exception("Couldn't download file!");
	return $res;
}

?>
