<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/settings.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.3
    $accountID = isset($_POST["udid"]) ? $f->checkString($_POST["udid"]) : "";
    $type = isset($_POST["type"]) ? $f->checkDefaultString($_POST["type"]) : "";

    // Check data
    if($accountID === "" OR is_numeric($accountID)) exit("-1");
    if($type === "" OR ($type != "top" AND $type != "relative")) exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        switch($type){
            case "top":
                $scoreCount = floor($scoreCount);

                $query = $db->prepare("SET @rownum := 0;"); $query->execute();
                $query = $db->prepare("SELECT @rownum := @rownum + 1 AS rownum, users.* FROM users WHERE stars > 0 AND scoreBan = 0 ORDER BY stars DESC LIMIT $scoreCount");
                $query->execute();
                $users = $query->fetchAll();

                if(count($users) == 0) exit("1:No players with more than zero stars:2:0:3:0:4:0:6:0:7:0:8:0:9:0:10:0:11:3");

                break;
            case "relative":
                $scoreCount = floor($scoreCount / 2);
                $usersTable = "users.* FROM users WHERE scoreBan = 0 ORDER BY stars DESC, demons DESC, userName DESC";

                $query = $db->prepare("SET @rownum := 0;"); $query->execute();
                $query = $db->prepare("SELECT a.rownum FROM (SELECT @rownum := @rownum + 1 AS rownum, $usersTable) AS a WHERE accountID = :accountID LIMIT 1");
                $query->execute([':accountID' => $accountID]);
                if($query->rowCount() != 0){
                    $rownum = $query->fetchColumn();

                    $query = $db->prepare("SET @rownum1 := 0;"); $query->execute();
                    $query = $db->prepare("SET @rownum2 := 0;"); $query->execute();
                    $query = $db->prepare("SELECT a.* FROM ((
                        SELECT b.* FROM (SELECT @rownum1 := @rownum2 + 1 AS rownum, $usersTable) AS b WHERE b.rownum < :rownum ORDER BY b.rownum DESC LIMIT $scoreCount
                    ) UNION (
                        SELECT c.* FROM (SELECT @rownum2 := @rownum2 + 1 AS rownum, $usersTable) AS c WHERE c.rownum >= :rownum ORDER BY c.rownum ASC LIMIT $scoreCount
                    )) AS a ORDER BY a.rownum ASC");
                    $query->execute([':rownum' => $rownum]);
                    $users = $query->fetchAll();
                } else {
                    $users = [];
                }

                if(count($users) == 0) exit("1:No players:2:0:3:0:4:0:6:0:7:0:8:0:9:0:10:0:11:3");

                break;
            default: exit("-1");
        }

        $scoreString = "";
        foreach($users AS $user){
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

        $scoreString = substr($scoreString, 0, -1);
        echo $scoreString;
    } else exit("-1");
?>