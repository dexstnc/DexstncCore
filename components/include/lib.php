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
                case "9":
                    return "!featured";
                    break;
                default:
                    return "Unknown action";
            }
        }
        public function mapPackDifficulty($difficulty){
            switch($difficulty){
                case "0":
                    return "auto";
                    break;
                case "1":
                    return "easy";
                    break;
                case "2":
                    return "normal";
                    break;
                case "3":
                    return "hard";
                    break;
                case "4":
                    return "harder";
                    break;
                case "5":
                    return "insane";
                    break;
                case "6":
                    return "demon";
                    break;
                default:
                    return "Unknown difficulty";
            }
        }
    }
?>