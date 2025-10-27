<?php

namespace Blaze\SmsService\Http\Controllers;

use App\Http\Controllers\Controller;
use Blaze\SmsService\Models\SmsTemplate;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 **/
class SmsTemplateController extends Controller
{
    // handle sms template callback from sms provider
    public function handleCallback()
    {
        $data = request()->all();

        $template = SmsTemplate::query()->find(request()->input('external_id'));

        if (! $template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        $updated = $template->handleCallback($data);

        if ($updated) {
            return response()->json(['message' => 'Template status updated'], 200);
        }

        return response()->json(['message' => 'Invalid status'], 400);
    }
}
