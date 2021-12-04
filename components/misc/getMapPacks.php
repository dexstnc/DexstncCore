<?php
    include dirname(__FILE__)."/../include/database.php";
    require_once dirname(__FILE__)."/../include/functions.php";
    $f = new Functions();

    $f->checkBanIP();

    // Data - version 1.6
    $page = isset($_POST["page"]) ? $f->checkNum($_POST["page"]) : "";

    // Check data
    if($page === "") exit("-1");

    if($_POST["secret"] === "Wmfd2893gb7"){
        $offset = $page*10;

        $query = $db->prepare("SELECT count(*) FROM mappacks");
        $query->execute();
        $mapPacksCount = $query->fetchColumn();

        $query = $db->prepare("SELECT * FROM mappacks LIMIT 10 OFFSET $offset");
        $query->execute();
        $mapPacks = $query->fetchAll();

        $mapPackString = "";
        foreach($mapPacks AS $mapPack){
            $mapPackLevels = $mapPack["level1"].','.$mapPack["level2"].','.$mapPack["level3"];

            // Output - version 1.6
            $mapPackString .= "1:".$mapPack["mapPackID"]; // mapPackID
            $mapPackString .= ":2:".$mapPack["mapPackName"]; // map pack name
            $mapPackString .= ":3:".$mapPackLevels; // levels
            $mapPackString .= ":4:".$mapPack["stars"]; // stars
            $mapPackString .= ":5:".$mapPack["coins"]; // coins
            $mapPackString .= ":6:".$mapPack["difficulty"]; // difficulty
            $mapPackString .= ":8:".$mapPack["mapPackNameColor"]; // map pack name color
            $mapPackString .= "|";
        }

        $mapPackString = substr($mapPackString, 0, -1);
        echo $mapPackString."#".$mapPacksCount.":".$offset.":10";
    } else exit("-1");
?>