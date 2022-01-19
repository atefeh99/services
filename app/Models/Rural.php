<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Rural extends Model
{
    protected $connection = 'gnaf';

    protected $table = 'rural';

    protected $hidden = [
        "id",
        "province_id",
        "county_id",
        "zone_id",
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
