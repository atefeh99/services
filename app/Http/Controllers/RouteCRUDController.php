<?php

namespace App\Http\Controllers;


use App\Http\Services\RouteCRUDServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;

class RouteCRUDController extends ApiController
{
    use RulesTrait;

    public function createItem(Request $request)
    {
        $input = $request->all();
        $data = self::checkRules(
            $input,
            __FUNCTION__,
            2000,
        );

        RouteCRUDServices::createItem($data);
        return $this->respondSuccessCreate($data);
    }

    public function readItem($id)
    {

        $data = self::checkRules(
            ['id' => $id],
            __FUNCTION__,
            3000,
        );

        $item = RouteCRUDServices::readItem($data['id']);
        return $this->respondItemResult($item);
    }

    public function updateField(Request $request, $id)
    {
        $input = $request->all();
        $input['id'] = $id;
        $data = self::checkRules(
            $input,
            __FUNCTION__,
            4000,
        );

        if (RouteCRUDServices::updateField($id, $data)) {
            return $this->respondSuccessUpdate($id);
        }


    }

    public function deleteRecord($id)
    {
        self::checkRules(
            ['id' => $id],
            __FUNCTION__,
            5000,
        );
        RouteCRUDServices::deleteRecord($id);
        return $this->respondSuccessDelete($id);
    }

    public function showAll()
    {
        $items = RouteCRUDServices::showAll();
        return $this->respondArray($items,count($items));
    }
}
