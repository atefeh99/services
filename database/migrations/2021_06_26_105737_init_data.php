<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Route;

class InitData extends Migration
{

    public function up()
    {
//        Route::createItem([
//            'id' => 1,
//            'description' => 'PositionByPostcode',
//            'uri' => '/api/v0/Postcode/PositionByPostcode',
//
//        ]);
//        Route::createItem([
//            'id' => 2,
//            'description' => 'WorkshopByPostcode',
//            'uri' => '/api/v0/Postcode/WorkshopByPostcode',
//
//        ]);
//        Route::createItem([
//            'id' => 3,
//            'description' => 'BuildingUnitsByPostcode',
//            'uri' => '/api/v0/Postcode/BuildingUnitsByPostcode',
//
//        ]);
//        Route::createItem([
//            'id' => 4,
//            'description' => 'ValidatePostCode',
//            'uri' => '/api/v0/Postcode/ValidatePostCode',
//
//        ]);
//        Route::createItem([
//            'id' => 5,
//            'description' => 'AddressByPostcode',
//            'uri' => '/api/v0/Postcode/AddressByPostcode',
//
//        ]);
//        Route::createItem([
//            'id' => 6,
//            'description' => 'TelephonesByPostcode',
//            'uri' => '/api/v0/Postcode/TelephonesByPostcode',
//
//        ]);
//        Route::createItem([
//            'id' => 7,
//            'description' => 'PostcodeByTelephone',
//            'uri' => '/api/v0/Telephone/PostcodeByTelephone',
//
//        ]);
//        Route::createItem([
//            'id' => 8,
//            'description' => 'AddressAndTelephones',
//            'uri' => '/api/v0/Postcode/AddressAndTelephones',
//
//        ]);
//        Route::createItem([
//            'id' => 9,
//            'description' => 'AddressStringByPostcode',
//            'uri' => '/api/v0/Postcode/AddressAndTelephones',
//
//        ]);
//        Route::createItem([
//            'id' => 10,
//            'description' => 'AddressByTelephone',
//            'uri' => '/api/v0/Telephone/AddressByTelephone',
//
//        ]);
//        Route::createItem([
//            'id' => 11,
//            'description' => 'ValidateTelephone',
//            'uri' => '/api/v0/Telephone/ValidateTelephone',
//
//        ]);
//        Route::createItem([
//            'id' => 12,
//            'description' => 'PositionByTelephone',
//            'uri' => '/api/v0/Telephone/PositionByTelephone',
//
//        ]);
//        Route::createItem([
//            'id' => 13,
//            'description' => 'AddressAndPostcodeByTelephone',
//            'uri' => '/api/v0/Telephone/AddressAndPostcodeByTelephone',
//
//        ]);
//        Route::createItem([
//            'id' => 14,
//            'description' => 'WorkshopByTelephone',
//            'uri' => '/api/v0/Telephone/WorkshopByTelephone',
//
//        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("Routes");
    }


}
