<?php
/** Fetching the excel sheet from Alko. */
function fetch_xlxs(): string {
    // Initialize a file URL to the variable
    $url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
    $res = file_get_contents($url);
    assert($res != false);
    return $res;
}
?>
