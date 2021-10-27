<?php

namespace Cr4sec\AppleAppsFlyer\Http\Controllers;

use Cr4sec\AppleAppsFlyer\Http\Requests\InitPaymentRequest;
use Cr4sec\AppleAppsFlyer\Models\Purchase;
use Cr4sec\AppleAppsFlyer\Models\Receipt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AppleController extends Controller
{
    private function isItNewPurchase(Collection $purchases, string $transactionId): bool
    {
        return $purchases->search(
                function ($item) use ($transactionId) {
                    return $item->transaction_id === $transactionId;
                }
            ) === false;
    }

    /**
     * @param  Receipt  $receipt
     * @param  array  $purchases
     * @return array
     */
    private function getNewPurchases(Receipt $receipt, array $purchases): array
    {
        $newPurchases = [];

        foreach ($purchases as $purchase) {
            if ($this->isItNewPurchase($receipt->purchases, $purchase['transaction_id'])) {
                $newPurchases[] = new Purchase([
                    'transaction_id' => $purchase['transaction_id'],
                    'is_trial' => $purchase['is_trial_period'] === 'true'
                ]);
            }
        }

        return $newPurchases;
    }

    /**
     * @param  InitPaymentRequest  $request
     */
    public function initPayment(InitPaymentRequest $request)
    {
        $purchases = $request
            ->input('receipt')
            ->getReceipt()['in_app'];

        $originalTransactionId = $purchases[0]['original_transaction_id'];

        $receipt = Receipt::with('purchases')
            ->whereOriginalTransactionId($originalTransactionId)
            ->first();

        if (!$receipt) {
            $receipt = new Receipt;
        }

        $receipt->original_transaction_id = $originalTransactionId;
        $receipt->currency = $request->input('currency');
        $receipt->price = $request->input('price');
        $receipt->idfa = $request->input('idfa');
        $receipt->appsflyer_id = $request->input('appsflyer_id');
        $receipt->user_id = auth()->user()->id;
        $receipt->save();

        $receipt
            ->purchases()
            ->saveMany($this->getNewPurchases($receipt, $purchases));
    }

    /**
     * @param  Request  $request
     */
    public function handler(Request $request)
    {
        $originalTransactionId = $request->input('unified_receipt')['pending_renewal_info'][0]['original_transaction_id'];

        /** @var Receipt|null $receipt */
        $receipt = Receipt::with('purchases')
            ->whereOriginalTransactionId($originalTransactionId)
            ->first();

        if (!$receipt) {
            $receipt = new Receipt;
            $receipt->original_transaction_id = $originalTransactionId;
            $receipt->save();
        }

        $receipt
            ->purchases()
            ->saveMany(
                $this->getNewPurchases(
                    $receipt,
                    $request->input('unified_receipt')['latest_receipt_info']
                )
            );
    }
}
