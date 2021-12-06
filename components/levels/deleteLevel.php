<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.7
    $accountID = isset($_POST["udid"]) ? $f->checkString($_POST["udid"]) : "";
    $levelID = isset($_POST["levelID"]) ? $f->checkNum($_POST["levelID"]) : "";

    // Check data
    if($accountID === "" OR is_numeric($accountID)) exit("-1");
    if($levelID === "") exit("-1");

    if($_POST["secret"] === "Wmfv2898gc9"){
        $userID = $f->getUserID($accountID);

        $query = $db->prepare("SELECT userID FROM levels WHERE levelID = :levelID AND deleted = 0");
        $query->execute([':levelID' => $levelID]);
        if($query->fetchColumn() == $userID){
            if(file_exists(dirname(__FILE__)."/../../data/levels/$levelID")) rename(dirname(__FILE__)."/../../data/levels/$levelID", dirname(__FILE__)."/../../data/levels/deleted/$levelID");

            $query = $db->prepare("UPDATE levels SET deleted = 1 WHERE levelID = :levelID AND deleted = 0");
            $query->execute([':levelID' => $levelID]);
            
            exit("1");
        } else exit("-1");
    } else exit("-1");
?>