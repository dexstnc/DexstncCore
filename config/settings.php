<?php
    $checkGameVersion["use"]   = false; // Checking the game version
    $checkGameVersion["value"] = 1; // Server version of the game
    /*  1 - version 1.0  */

    $checkUserCookies["use"]    = true; // Checking the user cookies for protection
    $checkUserCookies["secure"] = false;
    /*  true - transferring cookies over HTTPS
        false - it is possible to intercept cookies (if possible, connect an SSL certificate and set the value to true) */
?>