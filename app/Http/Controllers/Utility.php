<?php

namespace App\Http\Controllers;

trait Utility
{
    public static function calculatePages($count, $limit)
    {
        return ceil($count / $limit);
    }

    public static function getPagination($first, $current, $prev, $next, $last)
    {
        return [$first, $current, $prev, $next, $last];
    }

    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function generateBytes(int $length): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * @param string $string
     * @return string
     */
    public static function generateUrl(string $string): string
    {
        $letters = Array('ä'=>'a', ','=>'', 'ø'=>'dn', 'á'=>'a', 'ã'=>'a', 'â'=>'a', 'č'=>'c', 'ć'=>'c', 'ď'=>'d', 'ě'=>'e', 'é'=>'e', 'ë'=>'e', 'è'=>'e', 'ê'=>'e', 'í'=>'i', 'ï'=>'i', 'ì'=>'i', 'î'=>'i', 'ľ'=>'l', 'ĺ'=>'l', 'ń'=>'n', 'ň'=>'n', 'ñ'=>'n', 'ó'=>'o', 'ö'=>'o', 'ô'=>'o', 'ò'=>'o', 'õ'=>'o', 'ő'=>'o', 'ř'=>'r', 'ŕ'=>'r', 'š'=>'s', 'ś'=>'s', 'ť'=>'t', 'ú'=>'u', 'ů'=>'u', 'ü'=>'u', 'ù'=>'u', 'ũ'=>'u', 'û'=>'u', 'ý'=>'y', 'ž'=>'z', 'ź'=>'z', ' '=>'-', '_'=>'-', '°'=>'', 'à'=>'a', '+' => '-', '/' => 'x', '.' => '', '(' => '', ')' => '');
        $string = mb_strtolower(trim($string), 'UTF-8');
        $tmp = strtr($string, $letters);
        $new = "";
        for( $i = 1; $i <= strlen($tmp); $i++ )
        {
            $char = substr($tmp, $i, 1);
            $char1 = substr($tmp, $i-1, 1);

            if($char != '-' || $char1 != '-')
            {
                $new .= $char1;
            }
        }

        $chars = ['-','+','_'];

        if (in_array($new[0], $chars))
        {
            $new = substr($new, 1);
        }

        if (in_array($new[strlen($new)-1], $chars))
        {
            $new = substr($new, 0, strlen($new)-1);
        }

        return $new;
    }
}