<?php
    class Lib{
        public function getActionString($action){
            switch($action){
                case "1":
                    return "Level download";
                    break;
                case "2":
                    return "Like/Dislike on level";
                    break;
                case "3":
                    return "Like/Dislike on comment";
                    break;
                case "4":
                    return "Deleting a level";
                    break;
                case "5":
                    return "!rate";
                    break;
                case "6":
                    return "!unrate";
                    break;
                default:
                    return "Unknown action";
            }
        }
    }
?>