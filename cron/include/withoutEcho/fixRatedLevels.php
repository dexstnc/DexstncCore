<?php
    include dirname(__FILE__)."/../../../components/include/database.php";

    $query = $db->prepare("UPDATE levels SET rated = 0 WHERE stars = 0 AND rated = 1");
    $query->execute();

    $query = $db->prepare("UPDATE levels SET rated = 1 WHERE stars > 0 AND rated = 0");
    $query->execute();
?>