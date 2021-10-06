<?php

namespace App\Http\Controllers;


use App\Http\Services\RouteCRUDServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Helpers\OdataQueryParser;


class RouteCRUDController extends ApiController
{
    use RulesTrait;

    public function filter($request)
    {

        $odata_query = OdataQueryParser::parse($request->fullUrl());
        if (OdataQueryParser::isFails()) {
            return $this->respondInvalidParams('1001', OdataQueryParser::getErrors(), 'bad request');
        }
        if (isset($odata_query['skip'])) {
            $validate = Validator::make(['skip' => $odata_query['skip']], [
                'skip' => 'integer|required'
            ]);
            if ($validate->fails()) {
                return $this->respondInvalidParams('1021', $validate->errors(), 'bad request');
            }
            $skip = $odata_query['skip'];
        } else {
            $skip = env('DEFAULT_SKIP');
        }

        if (isset($odata_query['top'])) {
            $validate = Validator::make(['top' => $odata_query['top']], [
                'top' => 'integer|required'
            ]);
            if ($validate->fails()) {
                return $this->respondInvalidParams('1022', $validate->errors(), 'bad request');
            }
            $take = $odata_query['top'];
        } else {
            $take = env('DEFAULT_TOP');
        }

        return ['take' => $take, 'skip' => $skip];
    }


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

    public function showAll(Request $request)
    {
        $input = self::filter($request);
        $take = $input['take'];
        $skip = $input['skip'];

        $items = RouteCRUDServices::showAll($take, $skip);
        return $this->respondArray($items['items'], $items['count']);
    }
}
