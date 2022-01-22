<?php

return [
    'custom' => [
        '201' => 'successfully created',
        '400' => 'the given data is invalid',
        '401' => 'unauthorized',
        '403' => 'forbidden',
        '404' => 'resource not found',
        '405' => 'method not allowed',
        '409' => 'resource conflict',
        '429' => 'too many request exception, retry after :retry hours',
        '500' => 'internal server error',
        'token' => [
            'revoke' => 'successfully revoked',
            'client_revoke_notice' => 'your old token is going to revoke in 24 hours'
        ],
        'error' => [
            'no_data' => 'your request has no content or content is not valid',
            'try_later' => 'please try later',
            'exist_plan_for_next_period' => 'you can not create new invoice, because have plan for next period!',
            'fields_not_supplied' => 'fields not supplied',
            'validation_regex' => 'validation.regex',
            'empty_result' => 'empty result',
            'not_found' => 'not found',
            'resource_not_found' => 'resource not found',
            'model_not_found' => 'requested :model not found',
            'unauthorized' => 'unauthorized',
            'query' => 'query exception',
            'expire' => 'your code has expired',
            'ResMsg' => "ناموفق",
            'msg_part1' => 'خطای سرویس دهنده',
            'telMsg' => ':تلفن در بانک کدپستی موجود نیست',
            'postcodeMsg' => ': کدپستی در بانک کدپستی موجود نیست',
            'positionMsg' => ':مختصاتی برای کد جغرافیایی یافت نشد',
            'invalidPostcode' => 'کد پستی نامعتبر است',
            '2115' => 'خطا سیستمی ثبت درخواست',
            'unitMsg' => ': واحد دیگری برای این کد پستی موجود نیست',
            '9070' => ':یک خطای غیر منتظره اتفاق افتاده است',
            '2117' => 'خطای سیستمی فراخوانی سرویس',
            'activitycodeMsg' => ': کدفعالیت در بانک کدپستی موجود نیست',
            'transaction_part1' => 'تراکنش مالی با شماره ی ',
            'transaction_part2' => 'ناموفق می باشد',
            'transaction_not_found' => 'تراکنش مالی یافت نشد',
            '-8'=> 'خطای ذخیره درخواست',
            '1102'=>'تاریخ اعتبار گواهی پستی مورد نظر منقضی شده است',
            '1101'=>'کد رهگیری گواهی کد پستی معتبر نمی باشد',

        ],
        'success' => [
            'send' => 'send successfully',
            'update' => 'updated successfully',
            'create' => 'successfully created',
            'delete' => 'successfully deleted',
            'validation_email_sent' => 'validation email sent',
            'ok_validation' => 'your account has been successfully validated',
            'forgot_password' => 'please check your email to continue!',
            'unsubscribe' => 'you have unsubscribed successfully',
            'avaliablecode' => 'your code is available',
            'ResMsg' => "موفق",
            "in_progress" => "در حال انجام",
            "todo" => "درنوبت"
        ],

    ]
];
