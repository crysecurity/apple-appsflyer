<?php

namespace Cr4sec\AppleAppsFlyer\Observers;

use Cr4sec\AppleAppsFlyer\Models\Purchase;

class PurchaseObserver
{
    public function creating(Purchase $purchase)
    {
        $receipt = $purchase->receipt;

        if ($receipt->user_id !== null && $receipt->appsflyer_id !== null) {

            $payload = [
                'idfa' => $receipt->idfa,
                'appsflyer_id' => $receipt->appsflyer_id,
                'customer_user_id' => $receipt->user_id,
                'eventCurrency' => $receipt->currency,
                'ip' => "1.0.0.0",
                'eventTime' => date("Y-m-d H:i:s.000", time()),
                'af_events_api' => 'true',
                'eventName' => "af_start_trial",
                'eventValue' => "{\"af_revenue\":\"0\",\"af_content_id\":\"092\", \"af_content_type\": \"123\", \"renewal\":\"true\"}"
            ];

            if (!$purchase->is_trial) {
                $payload['eventName'] = "af_subscribe";
                $payload['eventValue'] = "{\"af_revenue\":\"$receipt->price\",\"af_content_id\":\"092\", \"af_content_type\": \"123\", \"renewal\":\"true\"}";
            }

            if (app('AppsFlyer')->sendEvent($payload)->status() === 200) {
                $purchase->sent_to_af = true;
            }
        }
    }
}
