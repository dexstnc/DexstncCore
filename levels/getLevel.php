<?php
    chdir(dirname(__FILE__));

    include "../include/database.php";
    require_once "../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.2
    $type = isset($_POST["type"]) ? $f->checkNum($_POST["type"]) : 1;
    $search = isset($_POST["str"]) ? $f->checkDefaultString($_POST["str"]) : "";
    $difficulty = isset($_POST["diff"]) ? $f->checkNumString($_POST["diff"]) : "-";
    $length = isset($_POST["len"]) ? $f->checkNumString($_POST["len"]) : "-";
    $page = isset($_POST["page"]) ? $f->checkNum($_POST["page"]) : 0;

    // Check data
    if($type === "") exit("-1");
    if($difficulty === "") exit("-1");
    if($length === "") exit("-1");
    if($page === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $offset = $page*10;
        $string = "";
        $where = ["deleted = 0"];
        $order = "uploadDate DESC";

        if($length !== "-"){
            if(is_numeric($length)){
                $where[] = "levelLength = $length";
            } else {
                $where[] = "levelLength IN ($length)";
            }
        }

        if($difficulty !== "-"){
            if($difficulty == -1){
                $where[] = "difficulty = 0";
            } elseif(is_numeric($difficulty)){
                $where[] = "difficulty = $difficulty";
            } else {
                $difficulty = str_replace(",", "0,", $diff)."0";
                $where[] = "difficulty IN ($difficulty)";
            }
        }

        switch($type){
            case "0": // Search levels
                if($search !== ""){
                    if(is_numeric($search)){
                        $where[] = "levelID = $search OR levelName LIKE '%$search%'";
                    } else {
                        $where[] = "levelName LIKE '%$search%'";
                    }
                }

                $order = "likes DESC, uploadDate DESC";
                break;
            case "1": // Most downloaded
                $order = "downloads DESC, uploadDate DESC";
                break;
            case "2": // Most liked
                $order = "likes DESC, uploadDate DESC";
                break;
            case "3": // Trending
                $where[] = "uploadDate > ".(time()-(7*86400));
                $order = "likes DESC, uploadDate DESC";
                break;
            case "4": // Most recent
                $order = "uploadDate DESC";
                break;
            case "5": // Wiev user more levels
                $where[] = "userID = $search";
                $order = "uploadDate DESC";
                break;
            case "6": // Featured
                $where[] = "rated = 1 AND rateDate > 0";
                $order = "rateDate DESC";
                break;
            default:
                exit("-1");
        }

        if(count($where) > 0) $string .= " WHERE (".implode(" ) AND ( ", $where).")";
        if($order) $string .= " ORDER BY $order";
        $string .= " LIMIT 10 OFFSET $offset";

        $query = $db->prepare("SELECT count(*) FROM levels".$string);
        $query->execute();
        $levelsCount = $query->fetchColumn();

        $query = $db->prepare("SELECT * FROM levels".$string);
        $query->execute();
        $levels = $query->fetchAll();
        
        $lvlString = "";
        $userString = "";
        foreach($levels AS $level){
            $multiString[] = $level['levelID'];

            $lvlString .= "1:".$level["levelID"]; // level ID
            $lvlString .= ":2:".$level["levelName"]; // level name
            $lvlString .= ":3:".base64_decode($level['levelDesc']); // level description
            $lvlString .= ":5:".$level["levelVersion"]; // level version
            $lvlString .= ":6:".$level["userID"]; // user ID
            $lvlString .= ":8:10:9:".$f->getRealDifficulty($level["difficulty"]); // level difficulty
            $lvlString .= ":10:".$level["downloads"]; // downloads on level
            $lvlString .= ":12:".$level["audioTrack"]; // audiotrack on level
            $lvlString .= ":13:".$level["gameVersion"]; // game version
            $lvlString .= ":14:".$level["likes"]; // likes on level
            $lvlString .= ":15:".$level["levelLength"]; // level length
            $lvlString .= "|";

            $userString .= $level['userID'].":".$f->getUserName($level['userID'])."|";
        }
        
        $lvlString = substr($lvlString, 0, -1);
        $userString = substr($userString, 0, -1);
        echo $lvlString."#".$userString;
        echo "#".$levelsCount.":".$offset.":10";
    } else exit("-1");
?>