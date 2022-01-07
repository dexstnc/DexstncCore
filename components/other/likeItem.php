<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();
    $ip = $f->getIP();

    // Data from version 1.1
    $itemID = $gp->getPost("itemID", "n"); if($itemID === "") exit("-1");
    $like = $gp->getPost("like", "n"); if($like === "") exit("-1");
    $type = $gp->getPost("type", "n"); if($type === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        if($like != 0 AND $like != 1) exit("-1");

        switch($type){
            case 1: // Like or Dislike level
                $table = "levels";
                $item = "levelID";
                $actionType = 2;
                $actionValue = "+/- like on level";
            break;
            case 2: // Like or Dislike comment
                $table = "comments";
                $item = "commentID";
                $actionType = 4;
                $actionValue = "+/- like on comment";
            break;
            default: exit("-1");
        }

        $query = $db->prepare("SELECT count(*) FROM actions WHERE type = :actionType AND itemID = :itemID AND IP = :ip");
        $query->execute([":actionType" => $actionType, ":itemID" => $itemID, ":ip" => $ip]);
        if($query->fetchColumn() == 0){
            $query = $db->prepare("SELECT likes FROM $table WHERE $item = :itemID LIMIT 1");
            $query->execute([":itemID" => $itemID]);
            if($query->rowCount() == 1){
                $likes = $query->fetchColumn();
                if($like == 1){ $likes++; } else $likes--;

                $query = $db->prepare("UPDATE $table SET likes = :likes WHERE $item = :itemID");
                $query->execute([":likes" => $likes, ":itemID" => $itemID]);

                $query = $db->prepare("INSERT INTO actions (type, value1, value2, actionDate, itemID, IP) VALUES (:actionType, :actionValue, :like, :time, :itemID, :ip)");
                $query->execute([":actionType" => $actionType, ":actionValue" => $actionValue, ":like" => $like, ":time" => time(), ":itemID" => $itemID, ":ip" => $ip]);

                exit("1");
            } else exit("-1");
        } else exit("-1");
    } else exit("-1");
?>