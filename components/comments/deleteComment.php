<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();

    $ip = $f->getIP();

    // Data - version 1.6
    $accountID = isset($_POST["udid"]) ? $f->checkString($_POST["udid"]) : "";
    $commentID = isset($_POST["commentID"]) ? $f->checkNum($_POST["commentID"]) : "";
    $levelID = isset($_POST["levelID"]) ? $f->checkNum($_POST["levelID"]) : "";

    // Check data
    if($accountID === "" OR is_numeric($accountID)) exit("-1");
    if($commentID === "") exit("-1");
    if($levelID === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $userID = $f->getUserID($accountID);

        $query = $db->prepare("SELECT levelID, deleted FROM comments WHERE (IP = :ip OR userID = :userID) AND commentID = :commentID LIMIT 1");
        $query->execute([':ip' => $ip, ':userID' => $userID, ':commentID' => $commentID]);
        if($query->rowCount() == 1){
            $commentInfo = $query->fetch();
            if($levelID == $commentInfo["levelID"] AND $commentInfo["deleted"] == 0){
                $query = $db->prepare("UPDATE comments SET deleted = 1 WHERE commentID = :commentID");
                $query->execute([':commentID' => $commentID]);
                
                exit("1");
            } else exit("-1");
        } else exit("-1");
    } else exit("-1");
?>