<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/settings.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.3
    $accountID = isset($_POST["udid"]) ? $f->checkString($_POST["udid"]) : "";
    $userName = isset($_POST["userName"]) ? $f->checkDefaultString($_POST["userName"]) : "";
    $stars = isset($_POST["stars"]) ? $f->checkNum($_POST["stars"]) : "";
    $demons = isset($_POST["demons"]) ? $f->checkNum($_POST["demons"]) : "";
    $icon = isset($_POST["icon"]) ? $f->checkNum($_POST["icon"]) : "";
    $color1 = isset($_POST["color1"]) ? $f->checkNum($_POST["color1"]) : "";
    $color2 = isset($_POST["color2"]) ? $f->checkNum($_POST["color2"]) : "";
    // Data - version 1.6
    $iconType = isset($_POST["iconType"]) ? $f->checkNum($_POST["iconType"]) : 0;
    $coins = isset($_POST["coins"]) ? $f->checkNum($_POST["coins"]) : 0;

    // Check data
    if($accountID === "" OR is_numeric($accountID)) exit("-1");
    if($userName === "") exit("-1");
    if($stars === "") exit("-1");
    if($demons === "") exit("-1");
    if($icon === "") exit("-1");
    if($color1 === "") exit("-1");
    if($color2 === "") exit("-1");
    if($iconType === "") exit("-1");
    if($coins === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        // Score limit
        if($scoreLimit === true){
            // Additional limit for stars
            $query = $db->prepare("SELECT SUM(stars) FROM levels");
            $query->execute();
            $maxStars = $scoreLimitStars + $query->fetchColumn();
            // Additional limit for demons
            $query = $db->prepare("SELECT SUM(demon) FROM levels");
            $query->execute();
            $maxDemons = $scoreLimitDemons + $query->fetchColumn();

            if($stars > $maxStars) exit("-1");
            if($demons > $maxDemons) exit("-1");
        }

        $userID = $f->getUserID($accountID, $userName);

        $query = $db->prepare("UPDATE users SET stars = :stars, demons = :demons, coins = :coins, iconType = :iconType, icon = :icon, color1 = :color1, color2 = :color2 WHERE userID = :userID");
        $query->execute([':stars' => $stars, ':demons' => $demons, ':coins' => $coins, ':iconType' => $iconType, ':icon' => $icon, ':color1' => $color1, ':color2' => $color2, ':userID' => $userID]);

        echo $userID;
    } else exit("-1");
?>