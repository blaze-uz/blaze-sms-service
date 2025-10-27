<?php

namespace Blaze\SmsService\Http\Controllers;


use App\Http\Controllers\Controller;
use Blaze\SmsService\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @date 10/22/2025 9:41 AM
 */
class SmsLogController extends Controller
{
    public function handleCallback(Request $request)
    {
        try {
            Log::info('SMS Callback received', [
                'payload' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $validated = $request->validate([
                'sms_id' => 'required',
                'status' => 'required',
                'phone_number' => 'required',
            ]);

            $smsLog = SmsLog::where('external_id', $validated['sms_id'])->first();

            if (! $smsLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMS not found',
                ], 404);
            }

            $smsLog->update([
                'status' => $validated['status'],
                'eskiz_status' => $request->input('eskiz_status', $validated['status']),
                'result_date' => $request->input('result_date') ?
                    \Carbon\Carbon::parse($request->input('result_date')) :
                    now(),
                'total_price' => $request->input('total_price'),
                'price' => $request->input('price'),
                'parts_count' => $request->input('parts_count'),
                'is_ad' => $request->boolean('is_ad'),
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('SMS Callback validation failed', [
                'errors' => $e->errors(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('SMS Callback processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
