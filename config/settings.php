<?php
    $checkGameVersion = false; // false - disable game version check; true - enable game version check
    $totalGameVersion = 7;
    /* 
    GAME VERSIONS
        1 - version 1.0
        2 - version 1.1
        3 - version 1.2
        4 - version 1.3
        5 - version 1.4
        6 - version 1.5
        7 - version 1.6
    */

    $cronRun = 300; // Run CRON once every N seconds
    
    $showRatedLevelsInFeatured = false; // Show rated levels in featured
    $ratedCPs = 1; // The number of creator points for rated level
    $featuredCPs = 1; // The number of creator points for featured level

    $disabledNames = ["Player", "RobTop"]; // Disabled names
    $uniqueNames = true; // false - users can be with the same name; true - only unique names
    $usersLimit = true; // false - disable user limit; true - enable user limit
    $usersLimitCount = 3; // The number of users for the limit
    $usersLimitTime = 3600; // Time of counting users for the limit (in seconds)

    $levelLimit = true; // false - disable level limit; true - enable level limit
    $levelLimitCount = 3; // The number of levels for the limit
    $levelLimitTime = 3600; // Time of counting levels for the limit (in seconds)

    $commentLimit = true; // false - disable comment limit; true - enable comment limit
    $commentLimitCount = 10; // The number of comments for the limit
    $commentLimitTime = 3600; // Time of counting comments for the limit (in seconds)
    $commentLimitAtLevel = true; // false - disable comment limit at level; true - enable comment limit at level
    $commentLimitAtLevelCount = 3; // The number of comments per level for the limit
    
    $likesLimit = true; // false - disable likes limit; true - enable likes limit
    $likesLimitCount = 20; // The number of likes for the limit
    $likesLimitTime = 3600; // Time of counting likes for the limit (in seconds)

    $scoreCount = 50; // Number of positions in the scores
    /*
        50 - version 1.3
    */
    $scoreLimit = true; // false - disable score limit; true - enable score limit
    $scoreLimitStars = 102 + 20; // Additional limit for stars
    /* 
    NUMBER OF STARS IN DIFFERENT VERSIONS
	28 - version 1.0
        36 - version 1.1
        45 - version 1.2
        55 - version 1.3
        ? - version 1.4 (I'm dont have this version)
        ? - version 1.5 (I'm dont have this version)
        102 - version 1.6
    */
    $scoreLimitDemons = 1 + 3; // Additional limit for demons
    /* 
    NUMBER OF DEMONS IN DIFFERENT VERSIONS
        0 - version 1.3
        1 - version 1.6
    */

    $commandRateLimit = true; // false - disable command rate limit; true - enable command rate limit
    $commandRateLimitCheckStars = true; // false - disable checking stars for difficulty; true - enable checking stars for difficulty
    $commandRateLimitStars = true; // false - disable checking minimum and maximum number of stars; true - enable checking minimum and maximum number of stars
    $commandRateLimitMinStars = 1; // Minimum number of stars
    $commandRateLimitMaxStars = 10; // Maximum number of stars
    $commandRateAutoCPs = true; // false - disable automatic delivery of creator points when using the command; true - enable automatic delivery of creator points when using the command
?>
