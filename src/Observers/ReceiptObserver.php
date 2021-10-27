<?php

namespace Cr4sec\AppleAppsFlyer\Observers;

use Cr4sec\AppleAppsFlyer\Models\Purchase;
use Cr4sec\AppleAppsFlyer\Models\Receipt;
use Illuminate\Database\Eloquent\Collection;

class ReceiptObserver
{
    public function saving(Receipt $receipt)
    {
        if ($receipt->user_id !== null) {
            /** @var Collection|Purchase[] $purchases */
            $purchases = $receipt
                ->purchases()
                ->unsent()
                ->get();

            $purchases->each(static function (Purchase $purchase) use ($receipt) {
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
                    $purchase->save();
                }
            });
        }
    }
}
