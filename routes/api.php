<?php


use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::post('api/sms/template/callback', [\Blaze\SmsService\Http\Controllers\SmsTemplateController::class, 'handleCallback'])->name('sms.template.callback');
    Route::post('api/sms/callback', [\Blaze\SmsService\Http\Controllers\SmsLogController::class, 'handleCallback'])->name('sms.callback');
});
