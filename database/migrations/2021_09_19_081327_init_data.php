<?php

use App\Models\Route;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Route::createItem([
            'id' => 1,
            'description' => 'token',
            'uri' => '/token',
            'fa_name' => 'سرویس دریافت توکن',
            'document_link' => '',

        ]);
        Route::createItem([
            'id' => 2,
            'description' => 'Version',
            'uri' => '/api/v0/BaseInfo/Version',
            'fa_name' => 'اطلاعات پایه-نسخه وب سرویس',
            'document_link' => '',

        ]);
        Route::createItem([
            'id' => 3,
            'description' => 'Constraint',
            'uri' => '/api/v0/BaseInfo/Constraint',
            'fa_name' => 'اطلاعات پایه-محدودیت های فراخوانی سرویس',
            'document_link' => '',

        ]);
        Route::createItem([
            'id' => 4,
            'description' => 'AddressByPostcode',
            'uri' => '/api/v0/Postcode/AddressByPostcode',
            'fa_name' => 'دریافت نشانی با کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 5,
            'description' => 'AddressStringByPostcode',
            'uri' => '/api/v0/Postcode/AddressAndTelephones',
            'fa_name' => 'دریافت نشانی به صورت رشته ای با کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 6,
            'description' => 'TelephonesByPostcode',
            'uri' => '/api/v0/Postcode/TelephonesByPostcode',
            'fa_name' => 'دریافت تلفن (های) ثابت با کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 7,
            'description' => 'AddressAndTelephones',
            'uri' => '/api/v0/Postcode/AddressAndTelephones',
            'fa_name' => 'دریافت نشانی و تلفن (های) ثابت با کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 8,
            'description' => 'PositionByPostcode',
            'uri' => '/api/v0/Postcode/PositionByPostcode',
            'fa_name' => 'دریافت مختصات جغرافیایی با کد پستی',
            'document_link' => '',

        ]);
        Route::createItem([
            'id' => 9,
            'description' => 'ActivityCodeByPostcode',
            'uri' => '/api/v0/Postcode/ActivityCodeByPostcode',
            'fa_name' => 'دریافت کد فعالیت با کد پستی',
            'document_link' => '',

        ]);

        Route::createItem([
            'id' => 10,
            'description' => 'WorkshopByPostcode',
            'uri' => '/api/v0/Postcode/WorkshopByPostcode',
            'fa_name' => 'دریافت اطلاعات کارگاه با کد پستی ',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 11,
            'description' => 'BuildingUnitsByPostcode',
            'uri' => '/api/v0/Postcode/BuildingUnitsByPostcode',
            'fa_name' => 'دریافت کد پستی واحدهای دیگر با یک کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 12,
            'description' => 'GenerateCertificate',
            'uri' => '/api/v0/Postcode/GenerateCertificate',
            'fa_name' => 'تولید گواهی کد پستی با کد پستی 10رقمی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 13,
            'description' => 'ValidatePostCode',
            'uri' => '/api/v0/Postcode/ValidatePostCode',
            'fa_name' => 'اعتبار سنجی کدپستی 10رقمی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 14,
            'description' => 'AccuratePosition',
            'uri' => '/api/v0/Postcode/AccuratePosition',
            'fa_name' => 'دریافت مختصات parcel به صورت lat و lon با کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 15,
            'description' => 'EstimatedPosition',
            'uri' => '/api/v0/Postcode/EstimatedPosition',
            'fa_name' => 'دریافت مختصات GPS به صورت lat و lon با کد پستی',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 16,
            'description' => 'PostcodeByTelephone',
            'uri' => '/api/v0/Telephone/PostcodeByTelephone',
            'fa_name' => 'دریافت کدپستی با تلفن ثابت',
            'document_link' => '',
        ]);


        Route::createItem([
            'id' => 17,
            'description' => 'AddressByTelephone',
            'uri' => '/api/v0/Telephone/AddressByTelephone',
            'fa_name' => 'دریافت نشانی با تلفن ثابت',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 18,
            'description' => 'AddressAndPostcodeByTelephone',
            'uri' => '/api/v0/Telephone/AddressAndPostcodeByTelephone',
            'fa_name' => 'دریافت کد پستی و نشانی با تلفن ثابت',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 19,
            'description' => 'ActivityCodeByTelephone',
            'uri' => '/api/v0/Telephone/ActivityCodeByTelephone',
            'fa_name' => 'دریافت کد فعالیت (ISIC V.4)با تلفن ثابت',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 20,
            'description' => 'WorkshopByTelephone',
            'uri' => '/api/v0/Telephone/WorkshopByTelephone',
            'fa_name' => 'دریافت اطلاعات کارگاه با تلفن ثابت',
            'document_link' => '',
        ]);

        Route::createItem([
            'id' => 21,
            'description' => 'PositionByTelephone',
            'uri' => '/api/v0/Telephone/PositionByTelephone',
            'fa_name' => 'دریافت مختصات حغرافیایی با تلفن ثابت',
            'document_link' => '',
        ]);
        Route::createItem([
            'id' => 22,
            'description' => 'ValidateTelephone',
            'uri' => '/api/v0/Telephone/ValidateTelephone',
            'fa_name' => 'اعتبارسنجی شماره تلفن ثابت',
            'document_link' => '',
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Route::query()->truncate();
    }
}
