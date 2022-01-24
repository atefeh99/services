<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $connection = 'gnaf';

    protected $table = 'zone';

    protected $hidden = [
        "id",
        "province_id",
        "county_id",
        "en_name",
        "code",
        "geocoded_point",
        "geometry",
        "grid_code",
        "opt_approval",
        "attachment",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
}
