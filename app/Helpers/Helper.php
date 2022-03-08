<?php

namespace App\Helpers;

use geoPHP;

class Helper
{
    public static function getPolygon($coordinates)
    {
        $polygon = '';
        foreach ($coordinates as $lat_lon) {
            $polygon .= "$lat_lon[0] $lat_lon[1],";
        }
        return substr($polygon,0,-1);
    }

}
