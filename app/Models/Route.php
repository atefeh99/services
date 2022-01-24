<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Route extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uri',
        'description',
        'fa_name',
        'document_link',

    ];
    protected $table = 'routes';

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
        if (isset($data['fa_name'])) {
            $item->uri = $data['uri'];
        }
        if (isset($data['document_link'])) {
            $item->uri = $data['uri'];
        }
        return $item->save();
    }

    public static function deleteRecord($id)
    {
        $item = self::findOrFail($id);
        $item->delete();
    }

    public static function showAll($take, $skip)
    {
        $count = self::all()->count();
        if ($skip > $count) {
            throw new ModelNotFoundException();
        }
        if ($take + $skip > $count) {
            $take = $count - $skip;
        }
        $items = self::all(['id', 'uri', 'description', 'fa_name', 'document_link'])
            ->skip($skip)
            ->take($take)
            ->toArray();
        if (count($items) > 0) {
            return ['items' => $items, 'count' => $count];
        } else {
            throw new ModelNotFoundException();
        }
    }
}
