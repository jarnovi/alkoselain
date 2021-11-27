<?php
/** Fetching the excel sheet from Alko. */
function fetch_xlxs(string $url, string $target): bool {
    // read data and save to file specified by $target, so that we can store a copy of the original data on the server.
    //$url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
    //$target= '../storage/alko-hinnasto.xlsx'; // maybe add a date when it was downloaded?

    // If we have an older version save it as backup
    if (file_exists($target)){
        rename($target, "../storage/alko-hinnasto_backup.xlsx");
    }

    return assert(file_put_contents($target, fopen($url, 'r')));
}

//fetch_xlxs();

?>
