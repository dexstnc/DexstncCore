<?php
    include dirname(__FILE__)."/../include/database.php";
    include dirname(__FILE__)."/../../config/levels.php";
    require_once dirname(__FILE__)."/../include/DXGetPost.php";
    $gp = new DXGetPost();
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();
    
    $f->checkBanIP();

    // Data from version 1.0
    $type = $gp->getPost("type", "n"); if($type === "") exit("-1");
    $str = $gp->getPost("str", "s+");
    $diff = $gp->getPost("diff", "ne+"); if($diff === "") exit("-1");
    $len = $gp->getPost("len", "ne+"); if($len === "") exit("-1");
    $page = $gp->getPost("page", "n"); if($page === "") exit("-1");
    // Data from version 1.3
    $star = $gp->getPost("star", "n", 0); if($star === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $queryString = "";
        $offset = $page*10;
        $where = ["deleted = 0"];
        $order = "uploadDate DESC";

        if($diff !== "-"){
            if($diff == -1){
                $where[] = "difficulty = 0";
            } elseif($diff == -2){
                $where[] = "demon = 1";
            } elseif(is_numeric($diff)){
                $where[] = "difficulty = $diff";
            } else $where[] = "difficulty IN ($diff)";
        }

        if($len !== "-"){
            if(is_numeric($len)){
                $where[] = "levelLength = $len";
            } else $where[] = "levelLength IN ($len)";
        }

        if($star !== 0) $where[] = "stars > 0";

        switch($type){
            case "0": // Search levels
                if($str !== ""){
                    if(is_numeric($str)){
                        $where[] = "levelID = $str OR levelName LIKE '%$str%'";
                    } else {
                        $where[] = "levelName LIKE '%$str%'";
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
            case "4": // Recent
                $order = "uploadDate DESC";
            break;
            case "5": // Wiev user levels
                if(is_numeric($str)){
                    $where[] = "userID = $str";
                    $order = "uploadDate DESC";
                } else exit("-1");
            break;
            case "6": // Featured
                switch($showInFeatured){
                    case 0:
                        $where[] = "difficulty > 0 OR featured = 1";
                    break;
                    case 1:
                        $where[] = "stars > 0 OR featured = 1";
                    break;
                    case 2:
                        $where[] = "featured = 1";
                    break;
                    default: exit("-1");
                }
                
                $order = "rateDate DESC, uploadDate DESC";
            break;
            default: exit("-1");
        }

        if(count($where) > 0) $queryString .= " FROM levels WHERE (".implode(") AND (", $where).")";
        if($order) $queryString .= " ORDER BY $order";

        $query = $db->prepare("SELECT count(*)".$queryString);
        $query->execute();
        $levelsCount = $query->fetchColumn();

        $query = $db->prepare("SELECT *".$queryString." LIMIT 10 OFFSET $offset");
        $query->execute();
        $levels = $query->fetchAll();

        $lvlString = ""; $userString = "";
        foreach($levels AS $level){
            $levelDesc = base64_decode($level["levelDesc"]);

            // Output for version 1.0
            $lvlString .= "1:".$level["levelID"]; // level ID
            $lvlString .= ":2:".$level["levelName"]; // level name
            $lvlString .= ":3:".$levelDesc; // level description
            $lvlString .= ":5:".$level["levelVersion"]; // level version
            $lvlString .= ":6:".$level["userID"]; // user ID
            $lvlString .= ":8:10:9:".($level["difficulty"] * 10); // level difficulty
            $lvlString .= ":10:".$level["downloads"]; // downloads on level
            $lvlString .= ":12:".$level["audioTrack"]; // audiotrack on level
            $lvlString .= ":13:".$level["gameVersion"]; // game version
            $lvlString .= ":14:".$level["likes"]; // likes on level
            $lvlString .= ":15:".$level["levelLength"]; // level length
            $lvlString .= ":16:0"; // likes - count = likes on level
            // Output for version 1.3
            $lvlString .= ":17:".$level["demon"]; // is demon
            $lvlString .= ":18:".$level["stars"]; // stars
            $lvlString .= "|";

            $userString .= $level["userID"].":".$f->getUserName($level["userID"])."|";
        }

        // Output in version 1.0
        echo substr($lvlString, 0, -1)."#".substr($userString, 0, -1)."#".$levelsCount.":".$offset.":10";
    } else exit("-1");
?>