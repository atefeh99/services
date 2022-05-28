<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'building';

    protected $connection = 'gnaf';

    public static function getItem($building_id)
    {
        return self::findOrFail($building_id)->toArray();
    }
}
