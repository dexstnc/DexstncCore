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
                    return "!delete";
                    break;
                case "5":
                    return "!rate";
                    break;
                case "6":
                    return "!unrate";
                    break;
                case "7":
                    return "!suggest";
                    break;
                case "8":
                    return "!setacc";
                    break;
                default:
                    return "Unknown action";
            }
        }
    }
?>