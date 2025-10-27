<?php

Route::middleware([
    'web',
    \App\Http\Middleware\InitializeTenancyByDomainWithLogging::class,
    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::post('api/sms/template/callback', [\Blaze\SmsService\Http\Controllers\SmsTemplateController::class, 'handleCallback'])->name('sms.template.callback');
});
