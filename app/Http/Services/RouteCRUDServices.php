<?php

namespace App\Http\Services;

use App\Models\Route;

class RouteCRUDServices
{
    public static function createItem($data)
    {
        return Route::createItem($data);
    }

    public static function readItem($id)
    {
        $item = Route::readItem($id);
        unset($item['created_at'], $item['updated_at'], $item['deleted_at']);
        return $item;
    }

    public static function updateField($id, $data)
    {
        return Route::updateField($id, $data);

    }

    public static function deleteRecord($id)
    {
        Route::deleteRecord($id);

    }

    public static function showAll()
    {
        return Route::showAll();
    }


}


