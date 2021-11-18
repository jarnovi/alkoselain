<?php
/** Fetching the excel sheet from Alko. */
function fetchXlsx(){
    // Initialize a file URL to the variable
    $url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
    
    $file_name = basename($url);
    
    if(file_put_contents( $file_name,file_get_contents($url))) {
        echo "File downloaded successfully";
    }
    else {
        echo "File downloading failed.";
    }
}

?>
