<?php

namespace App\Helper;

class Utiliti 
{
    public static function dd(... $values) 
    {
        echo '<pre>';
        var_dump($values);
        echo '</pre>';
        die();
    }

    public static function pd(... $values) 
    {
        echo '<pre>';
        print_r($values);
        echo '</pre>';
        die();
    }
}