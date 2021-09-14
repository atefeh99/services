<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

//use Illuminate\Support\Facades\DB;


class Route extends Model
{
    // use Common;
    protected $fillable = [
        'uri',
        'description',

    ];
    protected $table = 'routes';

//    protected static $_table = 'post_data_integrated';


    public static function createItem($data)
    {
        self::create($data);

    }

    public static function readItem($id)
    {
        $item = self::findOrFail($id);
        return $item->toArray();

    }

    public static function updateField($id, $data)
    {
        $item = self::findOrFail($id);
        if (isset($data['description'])) {
            $item->description = $data['description'];
        }
        if (isset($data['uri'])) {
            $item->uri = $data['uri'];
        }

        return $item->save();

    }

    public static function deleteRecord($id)
    {
        $item = self::findOrFail($id);
        $item->delete();
    }

    public static function showAll()
    {
        $items = self::all(['id', 'uri', 'description'])->toArray();
        if (count($items) > 0) {
           return $items;
        }else{
            throw new ModelNotFoundException();
        }
    }


}
