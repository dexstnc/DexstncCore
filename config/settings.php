<?php
    $checkGameVersion["use"]   = false; // Checking the game version
    $checkGameVersion["value"] = 4; // Server version of the game
    /*  1 - version 1.0
        2 - version 1.1
        3 - version 1.2
        4 - version 1.3  */

    $checkUserCookies["use"]    = true; // Checking the user cookies for protection
    $checkUserCookies["secure"] = false;
    /*  true - transferring cookies over HTTPS
        false - it is possible to intercept cookies (if possible, connect an SSL certificate and set the value to true) */
?>