<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();

    // Data from version 1.3
    $udid = $gp->getPost("udid", "su"); if($udid === "") exit("-1");
    $type = $gp->getPost("type", "s"); if($type === "") exit("-1");

    $scoreCount = 100;

    if($_POST["secret"] === "Wmfd2893gb7"){
        switch($type){
            case "top":
                $query = $db->prepare("SET @rownum := 0;"); $query->execute();
                $query = $db->prepare("SELECT @rownum := @rownum + 1 AS rownum, users.* FROM users WHERE stars > 0 AND scoreBan = 0 ORDER BY stars DESC, demons DESC, userName ASC LIMIT $scoreCount");
                $query->execute(); $users = $query->fetchAll();
            break;
            case "relative":
                $userID = $f->getUserID($udid, $userName);

                $usersTable = "users.* FROM users WHERE scoreBan = 0 ORDER BY stars DESC, demons DESC, userName ASC";

                $query = $db->prepare("SET @rownum := 0;"); $query->execute();
                $query = $db->prepare("SELECT a.rownum FROM (SELECT @rownum := @rownum + 1 AS rownum, $usersTable) AS a WHERE userID = :userID LIMIT 1");
                $query->execute([':userID' => $userID]);
                if($query->rowCount() != 0){
                    $rownum = $query->fetchColumn();

                    $query = $db->prepare("SET @rownum1 := 0;"); $query->execute();
                    $query = $db->prepare("SET @rownum2 := 0;"); $query->execute();
                    $query = $db->prepare("SELECT a.* FROM ((
                        SELECT b.* FROM (SELECT @rownum1 := @rownum1 + 1 AS rownum, $usersTable) AS b WHERE b.rownum <= :rownum ORDER BY b.rownum DESC LIMIT $scoreCount
                    ) UNION (
                        SELECT c.* FROM (SELECT @rownum2 := @rownum2 + 1 AS rownum, $usersTable) AS c WHERE c.rownum > :rownum ORDER BY c.rownum ASC LIMIT $scoreCount
                    )) AS a ORDER BY a.rownum ASC");
                    $query->execute([':rownum' => $rownum]); $users = $query->fetchAll();
                } else {
                    $users = [];
                }
            break;
            default: exit("-1");
        }

        if(count($users) == 0) exit("1:EMPTY:2:0");

        $scoreString = "";
        foreach($users AS $user){
            // Output for version 1.3
            $scoreString .= "1:".$user["userName"]; // userName
            $scoreString .= ":2:".$user["userID"]; // userID
            $scoreString .= ":3:".$user["stars"]; // stars
            $scoreString .= ":4:".$user["demons"]; // demons
            $scoreString .= ":6:".$user["rownum"]; // rank
            $scoreString .= ":7:".$user["accountID"]; // accountID
            $scoreString .= ":8:".$user["creatorPoints"]; // creator points
            $scoreString .= ":9:".$user["icon"]; // icon
            $scoreString .= ":10:".$user["color1"]; // color1
            $scoreString .= ":11:".$user["color2"]; // color2
            $scoreString .= "|";
        }

        echo substr($scoreString, 0, -1);
    } else exit("-1");
?>