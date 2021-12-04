<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.6
    $levelID = isset($_POST["levelID"]) ? $f->checkNum($_POST["levelID"]) : "";
    $rating = isset($_POST["rating"]) ? $f->checkNum($_POST["rating"]) : "";

    // Check data
    if($levelID === "") exit("-1");
    if($rating === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        // Lol. userID is undefined. Okay... This a problem
        exit("1");
    } else exit("-1");
?>