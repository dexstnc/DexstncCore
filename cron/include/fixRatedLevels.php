<?php
    include dirname(__FILE__)."/../../components/include/database.php";

    echo "Fix Rated Levels:<br>";

    $query = $db->prepare("SELECT count(*) FROM levels WHERE stars = 0 AND rated = 1");
    $query->execute();
    $count = $query->fetchColumn();

    $query = $db->prepare("UPDATE levels SET rated = 0 WHERE stars = 0 AND rated = 1");
    $query->execute();

    $query = $db->prepare("SELECT count(*) FROM levels WHERE stars > 0 AND rated = 0");
    $query->execute();
    $count += $query->fetchColumn();

    $query = $db->prepare("UPDATE levels SET rated = 1 WHERE stars > 0 AND rated = 0");
    $query->execute();

    echo 'Fixed '.$count.' level(-s)';
?>