<?php

trait ColorLog
{

    public static function textColor(String $str, String $type = 's')
    {
        switch ($type) {
            case 'e': //error
                return "\033[31m$str \033[0m";
                break;
            case 's': //success
                return "\033[32m$str \033[0m";
                break;
            case 'w': //warning
                return "\033[33m$str \033[0m";
                break;
            case 'i': //info
                return "\033[36m$str \033[0m";
                break;
            default:
                # code...
                break;
        }
    }

}
