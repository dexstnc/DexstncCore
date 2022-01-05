<?php
    $showInFeatured = 2; // Show levels with specific parameters
    /*  0 - levels have difficulty
        2 - levels have featured    */

    $autoRate["use"]   = true; // Users can rate the level
    $autoRate["count"] = 5; // The rating will be given to a level if N users rate it

    $checkLevelLength["use"] = true; // Checking the length of the level
    $checkLevelLength["min"] = 0; // Minimum length of the level
    $checkLevelLength["max"] = 3; // Maximum length of the level
    /*  0 -   Tiny
        1 -  Short
        2 - Medium
        3 -   Long  */

    $checkAudioTrack["use"] = false; // Checking audiotrack at the level
    $checkAudioTrack["count"] = 7; // Maximum audiotrack at the level
    /*  6 - version 1.0  
        7 - version 1.1  */
?>