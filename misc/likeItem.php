<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    $ip = $f->getIP();

    // Data - version 1.0
    $itemID = isset($_POST["itemID"]) ? $f->checkNum($_POST["itemID"]) : "";
    $like = isset($_POST["like"]) ? $f->checkNum($_POST["like"]) : "";
    $type = isset($_POST["type"]) ? $f->checkNum($_POST["type"]) : "";

    // Check data
    if($itemID === "") exit("-1");
    if($like === "") exit("-1");
    if($type === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        // Likes limit
        if($likesLimit === true){
            $query = $db->prepare("SELECT count(*) FROM actions WHERE (type = 2 OR type = 3) AND IP = :ip AND actionDate > :time");
            $query->execute([':ip' => $ip, ':time' => time()-$likesLimitTime]);
            if($query->fetchColumn() >= $likesLimitCount) exit("-1");
        }

        switch($type){
            case "1": // Like or dislike on level
                $table = "levels";
                $where = "levelID";
                $action = 2;
                break;
            case "2": // Like or dislike on comment
                $table = "comments";
                $where = "commentID";
                $action = 3;
                break;
            default: exit("-1");
        }

        $query = $db->prepare("SELECT count(*) FROM actions WHERE type = $action AND value1 = :itemID AND IP = :ip");
        $query->execute([':itemID' => $itemID, ':ip' => $ip]);
        if($query->fetchColumn() == 0){
            $query = $db->prepare("SELECT likes FROM $table WHERE $where = :itemID AND deleted = 0 LIMIT 1");
            $query->execute([':itemID' => $itemID]);
            if($query->rowCount() != 0){
                $likes = $query->fetchColumn();

                if($like == 0){
                    $likes--;
                } else $likes++;

                $query = $db->prepare("UPDATE $table SET likes = :likes WHERE $where = :itemID");
                $query->execute([':likes' => $likes, ':itemID' => $itemID]);
                $query = $db->prepare("INSERT INTO actions (type, value1, IP, actionDate) VALUES ($action, :itemID, :ip, :time)");
                $query->execute([':itemID' => $itemID, ':ip' => $ip, ':time' => time()]);

                exit("1");
            } else exit("-1");
        } else exit("-1");
    } else exit("-1");
?>
