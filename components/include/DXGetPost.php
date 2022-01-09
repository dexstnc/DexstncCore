<?php
    class DXGetPost{
        public function getPost($post, $type = "-", $default = ""){
            if(isset($_POST[$post])){
                $string = trim($_POST[$post]);
            } elseif(!empty($default) OR $default === 0){
                $string = trim($default);
            } else return "";

            switch($type){
                case "n": // Number
                    $string = preg_replace("/[^0-9]/", "", $string);
                break;
                case "n+": // Number list
                    $string = preg_replace("/[^0-9\,]/", "", $string);
                break;
                case "ne": // Number or empty
                    $string = preg_replace("/[^0-9\-]/", "", $string);
                break;
                case "ne+": // Number list or empty
                    $string = preg_replace("/[^0-9\,\-]/", "", $string);
                break;
                case "s": // String
                    $string = preg_replace("/[^a-zA-Z0-9]/", "", $string);
                break;
                case "s+": // String with space
                    $string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
                break;
                case "su": // String for user ID
                    $string = preg_replace("/[^a-zA-Z0-9\-]/", "", $string);
                break;
                case "sld": // String for level description
                    $string = preg_replace("/[^a-zA-Z0-9\s\,\.\:\-\!\?\/]/", "", $string);
                break;
                case "sls": // String for level string
                    $string = preg_replace("/[^a-zA-Z0-9\,\;]/", "", $string);
                break;
                case "sc": // String for comment
                    $string = preg_replace("/[^a-zA-Z0-9\s\,\.\:\-\!\?\/\(\)]/", "", $string);
                break;
            }

            return $string;
        }
    }
?>